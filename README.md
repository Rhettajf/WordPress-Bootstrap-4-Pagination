# WordPress-Bootstrap-4-Pagination
Bootstrap 4 Pagination for Roots Sage 9

In WordPress, numeric pagination can be a pain in the a**. This was especially true when paginating custom queries using Bootstrap paired with Sage 9 and Laravel Blade - not to mention I also needed an accessible and screen reader friendly solution. 

## Using boot_pagination within the Global Query

This function was written for Roots Sage 9 and uses the Laravel Blade Template Engine. When within the global/main query loop, you can call the boot_pagination() function with the following line of code: `{!! boot_pagination() !!}`

If your not using Blade, you can also call the boot_pagination() function with regular ol’ php: `<?php boot_pagination(); ?>`

## A simple example using boot_pagination within a Custom Post Type Query

The first thing we want to do is add the “Paged” Parameter to our Custom Post Type Query. If WP_Query is altering the main loop and the “paged” parameter is not set you’ll need to add it with `get_query_var()`. This is so WordPress knows exactly what page it’s on.

```php
@php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array('post_type' => 'work','posts_per_page'=>3, 'paged'=>$paged);
    $work = new WP_Query($args);
@endphp
```
The next step is to setup our loop and do all the fun things we have planned.
```php
@if($work)
    @while($work->have_posts()) @php($work->the_post())
        // fun stuff
    @endwhile
@endif
```
Finally, we will add our `boot_pagination()` function by setting the query argument via an array.
```php
@php
    $args = array(
        'query' => $work,
    );
    boot_pagination( $args ); 
@endphp
```
Of course, remember to restore the global $post variable of the main query loop: `@php(wp_reset_postdata())`
