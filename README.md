# Custom Post Sort

This plugin adds a custom field to WordPress posts. Users can specify the order in which posts should appear by adding a numeric value to the custom field. 

There are several ways to use the resulting custom field.

1. Use `orderby` to limit the loop to just those posts that have been assigned a custom sort value and hook into the theme with `pre_get_posts`.
2. Use an object of the `WP_Query` in a custom page template to create a curated list of ordered posts. 