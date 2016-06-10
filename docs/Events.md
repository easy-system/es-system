Introduction
============

After system initialization, the instance of events (`Es\Events\Events`) can be 
retrieved using system services:
```
$events = $services->get('Events');
```

# The system event
During initialization, the system will generate an `SystemEvent::INIT` event.
This event is intended for those listeners of components, that have to do 
anything at the stage of system initialization.

During the run, the system generates the following events:

- `SystemEvent::BOOTSTRAP`
- `SystemEvent::ROUTE`
- `SystemEvent::DISPATCH`
- `SystemEvent::RENDER`
- `SystemEvent::FINISH`

Additional components of the system should provide the processing of these 
events.

# The error event
On uncaught exception or error, the system generates an `ErrorEvent::FATAL_ERROR` 
event.

Additional components of the system should provide the processing of this
event.
