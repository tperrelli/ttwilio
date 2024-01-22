## Installation

`composer install``

## Set your Twilio phone on config/twilio.php

```
<?php

return [
    'from' => 'your-twilio-number-here'
];

```

## Endpoints

### Call's entry endpoint
`Route::get('calls/welcome', [CallController::class, 'welcome']);`

### Call's main menu flow
`Route::post('calls/flow', [CallController::class, 'menu']);`

### Call's to an agent by code
`Route::post('agents/call', [AgentController::class, 'find']);`

### Sends a message
`Route::post('messages/send', [MessageController::class, 'send']);`

### Ends the call
`Route::get('calls/goodbye', [CallController::class, 'hangup']);`