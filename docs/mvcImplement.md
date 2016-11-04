---
layout: default
title: How pattern is implemented?
current_menu: mvcImplement
---

# How pattern is implemented?

In Linna Framework every Mvc component is implemented as isolated, interaction between components happens through
[Dependency Injection](https://en.wikipedia.org/wiki/Dependency_injection) or 
through [Observer Pattern](https://en.wikipedia.org/wiki/Observer_pattern).

A Model is passed to View and Controller by class constructor (constructor injection).<br />
Model and View works as Observer Pattern.<br />
Controllers and Views are at same level, a Controller doesn't call a View and does not pass to it, data for render.<br />
View ask Model for obtain data for rendering.

## Work Flow

[![Mvc Diagram](img/mvc_diagram.png)]


### 1

### 2

### 3

### 4