<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Note;
new #[Layout('layouts.app')] class extends Component {
    public Note $note;

    public $noteTitle;
    public $noteBody;
    public $noteRecipient;
    public $noteSendDate;
    public $noteIsPublished;

    
    public function mount(Note $note)
    {
        $this->authorize('update', $note);
        $this->fill($note);
        $this->noteTitle=$note->title;
        $this->noteBody=$note->body;
        $this->noteRecipient=$note->recipient;
        $this->noteSendDate=$note->send_date;
        $this->noteIsPublished=$note->is_published;
    }

    public function saveNote(){
        $validated = $this->validate([
            'noteTitle' => ['required', 'string', 'min:4'],
            'noteBody' => ['required', 'string', 'min:10'],
            'noteRecipient' => ['required', 'email'],
            'noteSendDate' => ['required', 'date', 'after_or_equal:today'],
            
        ]);

        auth()
            ->user()
            ->notes()
            ->update([
                'title' => $validated['noteTitle'],
                'body' => $validated['noteBody'],
                'recipient' => $validated['noteRecipient'],
                'send_date' => $validated['noteSendDate'],
                'is_published'=>$this->noteIsPublished,
            ]);
        $this->dispatch('not-saved');
            $this->redirect(
                route('notes.index'),
                navigate:true);
    }

    
}; ?>


<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Notes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto space-y-4 sm:px-6 lg:px-8">

            <form wire:submit='saveNote' class="space-y-4">
                <x-input wire:model='noteTitle' label="Title" placeholder="What a greate day" />
                <x-textarea wire:model='noteBody' label='Your Note' placeholder='Share your note' />
                <x-input icon='user' wire:model='noteRecipient' label='Recipient' type='email'
                    placeholder='example@gmail.com' />
                <x-input icon='calendar' wire:model='noteSendDate' type='date' label='Send Date' />
                <x-checkbox label='Note published' wire:model='noteIsPublished'/>
                <div class="flex justify-between pt-4">
                    <x-button secondary  type="submit" spinner="saveNote">Save Note</x-button>
                    <x-button href="{{ route('notes.index') }}" wire:navigate flat negative>Back to Notes</x-button>
                </div>
                <x-action-message on="not-saved"/>
            </form>



        </div>
    </div>
</div>
