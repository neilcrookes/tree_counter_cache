# TreeCounterCache

An extension to CakePHP's core TreeBehavior that provides counterCache functionality for all children and/or direct children of each node in the tree. Packaged as a plugin with a fixture and unit tests with 100% code coverage.

## Usage

1. Install the plugin from my github account in your APP/plugins directory.

2. Add `child_count` and/or `direct_child_count` integer fields to your database table (you can configure the field names in the settings when you attach the behavior to the model).

3. Attach the TreeCounterCache Behavior to your model:
		<?php
		class MyTreeModel extends AppModel {
			var $name = 'MyTreeModel';
			var $actsAs = array('TreeCounterCache.TreeCounterCache');
		}
		?>

## Why?

Useful if you ever need to know whether a node has child nodes, e.g. for adding a CSS class to alter the style of that node to indicate to the user there is something beneath it. Caching the count saves extra db queries. To be honest, it's not that great really is it? Still, might be useful to someone.

## Todo

* Add counterScope functionality