<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [Entry Points Directory](#entry-points-directory)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

# Entry Points Directory

This directory should contain a subdirectory for each entry point defined in webpack. Each entry point will output a separate compiled JavaScript file. You may require dependencies of several file types in JavaScript, and webpack will compile with the required sourcemaps, data urls, relative URIs. Compiled code and files are output to `/client/build/` where they will be accessible to your `enqueue` functions or template tags. ES6+ syntax in your scripts will be transpiled by Babel.

Each entry subdirectory can contain files (JS or CSS) specific to that entry point. For example:

```shell
entries
├── global
│   └── index.js
├── homepage
│   └── index.js
└── article
    ├── index.js
    └── article.scss
```
