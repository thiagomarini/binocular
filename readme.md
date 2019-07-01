# Binocular

[![CircleCI](https://circleci.com/gh/thiagomarini/binocular.svg?style=svg)](https://circleci.com/gh/thiagomarini/binocular) [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Doing CQRS + Event Sourcing without building a spaceship. An attempt to bring event sourcing down from the over-engineering realm. 

Sometimes classic database models are not enough to represent the state of an application. If you find yourself creating database views or 
using heavy SQL queries to present data in different ways Binocular is for you, it will bring structure and order to your application.
A bit of a mindset shift will be necessary to work with events tough, you'll have to think about firing events and replaying them to calculate the state
of your application. But you don't need to do it everywhere or change the architecture of your application, you can only do event sourcing where database models are struggling to represent state.

This project focus only on 3 elements of ES + CQRS:

* **Event stream**: async stream of events produced by the application.
* **Projections**: will replay and process events from the event stream to calculate state.
* **Read models**: will cache the result of the event processing by the projection.

For more information please read [my post](https://medium.com/@marinithiago/doing-event-sourcing-without-building-a-spaceship-6dc3e7eac000) supporting the idea.

#### What's different about it?

* Binocular is super lightweight, it's not a full blown framework you'll need to refer to the docs all the time.
* Can be used only in parts of your application where database models are struggling to represent state.
* Projections use reducers to calculate the state of read models, a bit like [Redux](https://redux.js.org/basics/reducers): `previousState + event = newState`.
* Reducers make testing extremely simple, the same input always produces the same output.
* Actions and reducers are versioned so events can evolve drama-free.
* The only premise is that events need to be persisted so they can be replayed. 
* The project consists mostly of interfaces and base classes, you can extend it and implement it in whatever technology you prefer.


#### Why Binocular as project name?
Like CQRS, binocular vision happens when two separate images from two eyes are successfully combined into one image in the brain. CQRS has two eyes: the read and write eyes.

### Usage in a nutshell

```
composer require thiagomarini/binocular
```

```php
// save some events
$eventRepository->store(
    new UserSignedUp($userId, ['name' => 'John'])
);

$eventRepository->store(
    new UserNameWasUpdated($userId, ['name' => 'John Smith'])
);

// use a projection to process the events and calculate the state of its read model
$newState = $onboardingProjection->calculateState($userId);

// save the read model state
$readModelRepository->store($userId, $newState);

print_r($newState); // ['name' => 'John Smith']
```

See more examples on `tests/Examples` folder.

### How to contribute

PRs are welcome :)
