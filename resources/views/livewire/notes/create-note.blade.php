<?php

use Livewire\Volt\Component;

new class extends Component {
    public $noteTitle;
    public $noteBody;
    public $noteRecipient;
    public $noteSendDate;

    public function submit()
    {
        $validated = $this->validate([
            'noteTitle' => ['required', 'string', 'min:4'],
            'noteBody' => ['required', 'string', 'min:10'],
            'noteRecipient' => ['required', 'email'],
            'noteSendDate' => ['required', 'date', 'after_or_equal:today'],
        ]);

        auth()
            ->user()
            ->notes()
            ->create([
                'title' => $validated['noteTitle'],
                'body' => $validated['noteBody'],
                'recipient' => $validated['noteRecipient'],
                'send_date' => $validated['noteSendDate'],
                'is_published'=>true,
            ]);

            $this->redirect(
                route('notes.index'),
                navigate:true);
    }
}; ?>

<div>
    <form wire:submit='submit' class="space-y-4">
        <x-input wire:model='noteTitle' label="Title" placeholder="What a greate day" />
        <x-textarea wire:model='noteBody' label='Your Note' placeholder='Share your note' />
        <x-input icon='user' wire:model='noteRecipient' label='Recipient' type='email'
            placeholder='example@gmail.com' />
        <x-input icon='calendar' wire:model='noteSendDate' type='date' label='Send Date' />
        <div class="pt-4">
            <x-button primary right-icon='calendar' type="submit" spinner>Schedule Note</x-button>
        </div>

    </form>
</div>
