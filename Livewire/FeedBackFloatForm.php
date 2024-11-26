<?php

namespace Modules\Feedback\Livewire;


use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Route;

class FeedBackFloatForm extends Component
{

    public $showModal;
    public $tenantName;
    public $currentRoute;
    public $feature;
    public $testerName = '';
    public $description;

    public $uniqId;
    #[On('set-tester')]
    public function updateTesterName($testerName)
    {
        $this->testerName = $testerName;
    }

    protected $rules = [
        'testerName' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
    ];

    public function mount()
    {
        $this->tenantName = $this->extractTenantName();
        $this->currentRoute = request()->path();
        $this->feature = $this->detectFeature();
        $this->uniqId=uniqid();
    }

    public function detectFeature()
    {
        $route = Route::current();
        $controller = class_basename($route->controller);
        $action = $route->getActionMethod();

        if ($controller && $action) {
            $feature = strtolower(str_replace('Controller', '', $controller));
            return "{$feature} ({$controller}, {$action})";
        }

        return null;
    }

    public function extractTenantName()
    {
        $host = request()->getHost();
        return explode('.', $host)[0] ?? 'unknown';
    }

    public function storeFeedback()
    {
        $this->validate();

        // Simulate storing feedback (replace with actual DB logic).
        $feedback = [
            'tenant_name' => $this->tenantName,
            'current_route' => $this->currentRoute,
            'feature' => $this->feature,
            'tester_name' => $this->testerName,
            'description' => $this->description,
        ];

        // Save logic goes here (e.g., database save)

        session()->flash('message', 'Feedback submitted successfully!');
        $tester_name = $this->testerName;
        $this->dispatch('gettester',tester_name:"$tester_name");

        // Send feedback to Viber using the channel API
        $viberApiUrl = 'https://chatapi.viber.com/pa/post';
        $viberApiKey = '53b6cdda13380d4f-24466e12819a31ab-9fe614fb658dcc94'; // Replace with your actual Viber API key

        $viberMessage = [ // Replace with the actual Viber user ID
            'min_api_version' => 1,
            'sender' => [
                'name' => 'Feedback Bot',
            ],
            'tracking_data' => 'tracking data',
            'type' => 'text',
            'text' => "New feedback received from {$this->testerName}: {$this->description}"
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post($viberApiUrl, [
            'headers' => [
            'Content-Type' => 'application/json',
            'X-Viber-Auth-Token' => $viberApiKey
            ],
            'json' => $viberMessage
        ]);

        if ($response->getStatusCode() !== 200) {
            session()->flash('error', 'Failed to send feedback to Viber.');
        }
        // $this->showModal = false;
        $this->description = '';

    }

    public function render()
    {

        return view('feedback::Livewire.feedback-float-form');
    }
}
