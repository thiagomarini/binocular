# Binocular

[![CircleCI](https://circleci.com/gh/thiagomarini/binocular.svg?style=svg)](https://circleci.com/gh/thiagomarini/binocular) [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Doing CQRS + Event Sourcing without building a spaceship. An attempt to bring event sourcing down from the over-engineering realm. 

The aim of this project is to enable you to do CQRS + ES in PHP using your current stack, without the need to adopt any new technology.
Sometimes classic database models are not enough to represent the state of an application. If you find yourself creating database views or 
using heavy SQL queries to present data in different ways Binocular is for you, it will bring structure and order to your application.
A bit of a mindset shift is necessary to work with events tough, you'll have to think about producing and consuming events. 
But you don't need to do it everywhere or change the architecture of your application, you can do it only where database models are struggling to represent state.

This project focus only on 3 elements of ES + CQRS:

* **Event stream**: async stream of events produced by the application. The write side.
* **Projections**: will replay and process events from the event stream to calculate state.
* **Read models**: will cache the result of the event processing by the projection. The read side.

For more information please read [my post](https://medium.com/@marinithiago/doing-event-sourcing-without-building-a-spaceship-6dc3e7eac000) supporting the idea.

#### What's different about it?

* Binocular is super lightweight and can be used with any framework. 
* Should only be used where database models are struggling to represent state in the application.
* Projections use reducers to calculate the state of read models, a bit like [Redux](https://redux.js.org/basics/reducers): `previousState + event = newState`. Reducers make testing extremely simple, the same input always produces the same output.
* Actions and reducers are versioned so events can evolve drama-free.
* The only premise is that events need to be persisted somewhere so they can be replayed. 
* The project consists mostly of interfaces and base classes, you'll need to make your own implementation and know where to place things.


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

### Laravel Example

As already explained Binocular can be used with any framework, you just need to know where to place things.
In the case of Laravel, it already has a simple [observer implementation](https://laravel.com/docs/master/events) which is more than enough to make things work with Binocular. 
Conceptually you'll need to:

* Create an Eloquent implementation of the repository if you don't want to use the PDO one.
* Create migrations for event and read model table.
* Create an implementation of the `event()` global helper in order to save the event before queueing it.
* Place a projection in an event listener to calculate and save the state of the read model.

See more examples on `tests/Examples` folder.

### How to contribute

PRs are welcome :)
