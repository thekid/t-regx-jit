<p align="center">
    <a href="https://t-regx.com"><img src="t.regx.png" alt="T-Regx"></a>
</p>
<p align="center">
    <a href="https://github.com/T-Regx/T-Regx/actions/"><img src="https://github.com/T-Regx/T-Regx/workflows/build/badge.svg" alt="Build status"/></a>
    <a href="https://coveralls.io/github/T-Regx/T-Regx"><img src="https://coveralls.io/repos/github/T-Regx/T-Regx/badge.svg" alt="Unit tests"/></a>
    <a href="https://github.com/T-Regx/T-Regx/releases"><img src="https://img.shields.io/badge/latest-0.41.2-brightgreen.svg?style=popout" alt="latest: 0.41.2"/></a>
    <a href="https://github.com/T-Regx/T-Regx"><img src="https://img.shields.io/badge/dependencies-0-brightgreen.svg" alt="dependencies: 0"/></a>
</p>

# T-Regx | Regular Expressions library

PHP regular expressions brought up to modern standards.

[See documentation](https://t-regx.com/) at [t-regx.com](https://t-regx.com/).

[![last commit](https://img.shields.io/github/last-commit/T-Regx/T-Regx/develop.svg)](https://github.com/T-Regx/T-Regx/commits/develop)
[![commit activity](https://img.shields.io/github/commit-activity/y/T-Regx/T-Regx.svg)](https://github.com/T-Regx/T-Regx)
[![Commits since](https://img.shields.io/github/commits-since/T-Regx/T-Regx/0.41.2/develop.svg)](https://github.com/T-Regx/T-Regx/compare/0.41.2...develop)
[![Unit tests](https://img.shields.io/badge/Unit%20tests-3867-brightgreen.svg)](https://github.com/T-Regx/T-Regx)
[![Unit tests](https://img.shields.io/badge/Assertions-6079-brightgreen.svg)](https://github.com/T-Regx/T-Regx)
[![Code Climate](https://img.shields.io/codeclimate/maintainability/T-Regx/T-Regx.svg)](https://codeclimate.com/github/T-Regx/T-Regx)
[![Repository size](https://img.shields.io/github/languages/code-size/T-Regx/vanilla.svg?label=size)](https://github.com/T-Regx/T-Regx)
[![FQN](https://img.shields.io/badge/FQN-used-blue.svg)](https://github.com/kelunik/fqn-check)
[![PRs Welcome](https://img.shields.io/badge/PR-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)
[![Gitter](https://badges.gitter.im/T-Regx/community.svg)](https://gitter.im/T-Regx/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

[![OS Arch](https://img.shields.io/badge/OS-32&hyphen;bit-brightgreen.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-64&hyphen;bit-brightgreen.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Windows-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Linux/Unix-blue.svg)](https://github.com/T-Regx/T-Regx/actions)

[![PHP Version](https://img.shields.io/badge/PHP-7.1-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.2-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.3-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.4-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.1-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://github.com/T-Regx/T-Regx/actions)

1. [Installation](#installation)
    * [Composer](#installation)
2. [API](#api)
    1. [For standard projects -`pattern()`](#written-with-clean-api)
    2. For legacy projects - `preg::match_all()`
3. [Documentation](#documentation)
4. [T-Regx fiddle - Try online](#try-it-online-in-your-browser)
5. [Overview](#why-t-regx-stands-out)
    1. [Prepared patterns](#prepared-patterns)
    2. [Working with the developer](#working-with-the-developer)
    3. [Clean API](#written-with-clean-api)
    4. [Fatal errors](#protects-you-from-fatal-errors)
    5. [Clean Code](#t-regx-follows-the-philosophy-of-uncle-bob-and-clean-code)
    6. [Exceptions vs. errors](#exceptions-over-warningserrors)
6. [Comparison](#whats-better)
    1. [Exceptions over warnings/errors](#exceptions-over-warningserrors)
    2. [Working with the developer](#working-with-the-developer)
    3. [Written with clean API in mind](#written-with-clean-api)
    4. [Philosophy of Uncle Bob and "Clean Code"](#t-regx-follows-the-philosophy-of-uncle-bob-and-clean-code)
7. [Plans for the future](#current-work-in-progress)
8. [Sponsors](#sponsors)
9. [License](#license)

[Buy me a coffee!](https://www.buymeacoffee.com/danielwilkowski)

# Installation

Installation for PHP 7.1 and later (PHP 8 as well):

```bash
composer require rawr/t-regx
```

T-Regx only requires `mb-string` extension. No additional dependencies or extensions are required.

# API

**You** choose the interface:

- I choose the **modern regex API**:

  [Scroll to see](#written-with-clean-api) - `pattern()->test()`, `pattern()->match()`, `pattern()->replace()`

- I choose to **keep PHP methods** *(but protected from errors/warnings)*:

  [Scroll to see](#exceptions-over-warningserrors) - `preg::match_all()`, `preg::replace_callback()`, `preg::split()`

For legacy projects, we suggest `preg::match_all()`. For standard projects, we suggest `pattern()`.

- Standard T-Regx
  ```php
  $pattern = Pattern::of("ups"); // pattern("ups") also works
  $matcher = $pattern->match('yay, ups');
  
  if ($matcher->test()) {
    echo "Unmatched subject :/";
  }
  
  foreach ($matcher as $detail) {
    $detail->text();    // (string) "ups";
    $detail->offset();  // (int) 0
  }
  
  $pattern->replace('well, ups')->with('heck') // (string) "well, heck";
  ```

- Legacy API

  ```php
  try {
      preg::match_all('/?ups/', 'ups', $match, PREG_PATTERN_ORDER);
      echo $match[0][0];
  } catch (\TRegx\Exception\MalformedPatternException $exception) {
      echo "Invalid pattern";
  }
  ```

# Documentation

Full API documentation is available at [t-regx.com](https://t-regx.com/). List of changes is available
in [ChangeLog.md](https://github.com/T-Regx/T-Regx/blob/develop/ChangeLog.md).

# Try it online, in your browser!

Open [T-Regx fiddle](https://repl.it/github/T-Regx/fiddle) and start playing around right in your browser.
[Try now!](https://repl.it/github/T-Regx/fiddle)

# Why T-Regx stands out?

:bulb: [See documentation at t-regx.com](https://t-regx.com/)

* ### Prepared patterns

  Using user data isn't always safe with PCRE (even with `preg_quote()`), as well as just not being that
  convenient to use. T-Regx provides dedicated solution for building patterns with unsafe user input.
  Choose `Pattern::inject()` for simply including user data as literals. Use `Pattern::mask()` to convert user-supplied
  masks into full-fledged patterns, safely. Use `Pattern::template()` for constructing more complex patterns.

  ```php
  function makePattern($name): Pattern {
    if ($name === null) {
      return Pattern::of("name[:=]empty");
    }
    return Pattern::inject("name[:=]@;", [$name]); // inject $name as @
  }
  
  $gibberish = "(my?name)";
  $pattern = makePattern($gibberish);
  
  $pattern->test('name=(my?name)'); // (bool) true
  ```

* ### Working **with** the developer
    * Simple methods
        * T-Regx exposes functionality by simple methods, which return `int`, `string`, `string[]` or `bool`, which
          aren't nullable. If you wish to do something with your match or pattern, there's probably a method for that,
          which does exactly and only that.
    * Handlers and state:
        * Not even touching your error handlers or exception handlers **in any way**!
        * In fact, T-Regx doesn't touch any global state.
    * Strings:
        * [Fixing error with multibyte offset (utf-8 safe)](https://t-regx.com/docs/match-details#offsets).
        * Separate methods for positions:
            * `offset()` - which returns position of a match in characters in UTF-8
            * `byteOffset()` - which returns position of a match in bytes, regardless of encoding
    * Groups:
        * When using `preg::match_all()`, we receive an array, of arrays, of arrays. In contrast, T-Regx
          returns an array of groups: `Group[]`. Object `Group` contains all the information about the group.

        * Group errors:
            * When invalid group named is used `get('!@#')`, T-Regx throws `\InvalidArgumentException`.
            * When attempt to read a missing group, T-Regx throws `NonexistentGroupException`.
            * When reading a group that happens not to be matched, T-Regx throws `GroupNotMatchedException`.

* ### Written with clean API
    * Descriptive, simple interface
    * UTF-8 support out-of-the-box
    * No Reflection used, `No (...varargs)`, `No (boolean arguments, true)`, `(No flags, 1)`, `[No [nested, [arrays]]]`
    * Inconsistencies between PHP versions are eliminated in T-Regx

* ### Protects you from fatal errors
  Certain arguments cause fatal errors with `preg_()` methods, which terminate the application and can't be caught.
  T-Regx will predict if given argument would cause a fatal error, and will throw a catchable exception instead.

* ### T-Regx follows the philosophy of Uncle Bob and "Clean Code"

  Function should do one thing, it should do it well. A function should do exactly what you expect it to do.

* ### Compatible with other tools and libraries

  Granted, `Pattern::of()` accepts undelimited pattern (`(Foo){3,4}}`) is not suitable with other PHP libraries,
  which work with delimited patterns (`/(Foo){3,4}/`), for example Laravel and Routing. For that case,
  use `PcrePattern::of()` which accepts plain-old standard PHP syntax.

* ### Exceptions over warnings/errors
    * Unlike PHP methods, T-Regx doesn't use warnings/notices/errors for unexpected inputs:
      ```php
      try {
        preg::match_all('/([a3]+[a3]+)+3/', 'aaaaaaaaaaaaaaaaaaaa 3');
      } catch (\TRegx\SafeRegex\Exception\CatastrophicBacktrackingException $exception) {
        // caught
      }
      ```
    * Detects **malformed patterns** in and throws `MalformedPatternException`. This is impossible to catch
      with `preg_last_error()`.
      ```php
      try {
        preg::match('/?ups/', 'ups');
      } catch (\TRegx\Exception\MalformedPatternException $exception) {
        // caught
      }
      ```
    * Not every error in PHP can be read from `preg_last_error()`, however T-Regx throws dedicated exceptions for those
      events.

# What's better

![Ugly api](https://t-regx.com/img/external/readme/preg.png)

or

![Pretty api](https://t-regx.com/img/external/readme/t-regx.png?)

# Current work in progress

Current development priorities, regarding release of 1.0:

- Separate SafeRegex and CleanRegex into to two packages, so users can choose what they want #103
- Add documentation to each T-Regx public method #17 \[in progress]
- Revamp of [t-regx.com](https://t-regx.com/) documentation \[in progress]
- Release 1.0

# Sponsors

- [Andreas Leathley](https://github.com/iquito) - developing [SquirrelPHP](https://github.com/squirrelphp)
- [BarxizePL](https://github.com/BarxizePL) - Thanks!

# T-Regx is developed thanks to

<a href="https://www.jetbrains.com/?from=T-Regx">
  <img src="https://t-regx.com/img/external/jetbrains-variant-4.svg" alt="JetBrains"/>
</a>

## License

T-Regx is [MIT licensed](LICENSE).
