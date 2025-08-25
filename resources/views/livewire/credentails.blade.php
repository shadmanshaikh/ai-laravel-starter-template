<?php

use Livewire\Volt\Component;
use App\Models\APIKEY;
use Mary\Traits\Toast;
new class extends Component {
    //
    use Toast;
    public $apikey = "";
    public $selectedname;
    public function save(){ 
        APIKEY::create([
            'name' => $this->selectedname,
            'key' => $this->apikey,
        ]);
            $this->success("saved key successfully");
            $this->apikey = "";
            $this->selectedname = "";

    }
   public array $llms = [
    ['id' => "open AI" , 'name' => 'Open AI'],
    ['id' => "Google Gemini" , 'name' => 'Google Gemini'],
    ['id' => "DeepSeek" , 'name' => 'DeepSeek'],
];

}; ?>

<div>
    <x-header title="Credentails" separator progress-indicator/>
    <x-header title="Add your API Key" subtitle="Get credentails from OpenAI , Gemini , Grok or Ollama. " />
    <div class="main-frame">
        <x-card>
            <x-form wire:submit="save"> 
               <x-select label="Select LLM" :options="$llms" wire:model="selectedname"/> 
                <x-input label="Enter Your API key" placeholder="eg, AH0183" wire:model="apikey"/>
                <x-button label="Add" class="btn-primary mt-3" type="submit" icon-right="o-plus" spinner/>
            </x-form> 
        </x-card>
    </div>

</div>
