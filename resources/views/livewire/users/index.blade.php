<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\APIKEY;
new class extends Component {
    use Toast;

    public string $search = '';
    public bool $drawer = false;
    public $input = '';
    public $output = '';
    public function aiSearch(){
        // dd($this->input);

        //getting the gemini api prompt 
        $apiKey = APIKEY::latest()->first()->key;
        $apiName = APIKEY::latest()->first()->name;

        if($apiName == 'Google Gemini'){
            $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $this->input]
                    ]
                ]
            ]
        ]);

        $data = $response->json();
        $text = data_get($data, 'candidates.0.content.parts.0.text');
        $this->output = is_string($text) && $text !== ''
            ? $text
            : json_encode($data, JSON_PRETTY_PRINT);
        }elseif ($apiName == 'Open AI') {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type'  => 'application/json',
                ])->post("https://api.openai.com/v1/chat/completions", [
                    'model' => 'gpt-4o-mini', // or 'gpt-4o', 'gpt-3.5-turbo'
                    'messages' => [
                        ['role' => 'user', 'content' => $this->input],
                    ],
                ]);

                $data = $response->json();
                $text = data_get($data, 'choices.0.message.content');
                $this->output = is_string($text) && $text !== ''
                    ? $text
                    : json_encode($data, JSON_PRETTY_PRINT);

            } elseif ($apiName == 'DeepSeek') {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type'  => 'application/json',
                ])->post("https://api.deepseek.com/v1/chat/completions", [
                    'model' => 'deepseek-chat', // or 'deepseek-coder'
                    'messages' => [
                        ['role' => 'user', 'content' => $this->input],
                    ],
                ]);

                $data = $response->json();
                $text = data_get($data, 'choices.0.message.content');
                $this->output = is_string($text) && $text !== ''
                    ? $text
                    : json_encode($data, JSON_PRETTY_PRINT);
            }


    }
    public function with(): array
    {
        return [
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header icon="o-sparkles" title="Prompt Your AI" separator progress-indicator>
 
    </x-header>

        <x-textarea label="Enter Your Prompt" rows="5" placeholder="Enter your message" wire:model="input"/>
        <x-button label="send" class="btn-primary mt-3" icon-right="o-paper-airplane" wire:click="aiSearch"/>
        <h3 class="mt-4 font-semibold">Output</h3>
        <div class="p-4 border rounded bg-gray-20 prose max-w-none">
            {!! Str::markdown($output ?? '') !!}
        </div>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass" @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>
</div>
