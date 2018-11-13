# Jubjubbird.Respects—based on Buttercup.Protects

[![Build Status](https://travis-ci.org/jubjubbird/respects.png?branch=master)](https://travis-ci.org/jubjubbird/respects)

**Buttercup.Protects** is a PHP library for building Aggregates that protect business invariants, and that
record Domain Events.

**Jubjubbird.Respects** is a CQRS/ES library based on Buttercup.Protects but modified for easier interoperability with PhpSpec. The main issue were the final modifiers, so Prophecy (the mocking engine of PhpSpec) could not subclass-decorate them.  
