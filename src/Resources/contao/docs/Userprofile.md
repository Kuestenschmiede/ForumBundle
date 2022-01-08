User profile feature
====================

The forum post template is extended by a left column showing information about the author of this post.


## Avatars

Forum members can upload images used as their avatars through the "My Account" page.
The Size for the avatar can be set in the forum frontend module.
Otherwise a default size of 100 x 100 px is used.

## Signature

Forum members can enter a signature through the "My Account" page.
The signature is displayed beneath all of their forum posts.

## Post count

The post count is displayed beneath the author's name in the forum posts view.

## Additional links

Forum members can enter links to their homepage, facebook, twitter and google+ through the "My Account" page.
These information are displayed beneach the authors name inthe forum posts view.
To automatically add new links in the frontend output, add an eval array of "memberLink" => true to the new dca field 
(see field "memberHomepageLink" in tl_member of c4g_forum module for reference).

## Online status

Next to the authors name the online status of the author is displayed.
If the author is logged in and did perform an action over the past 5 minutes (default value, can be changed in the forum frontend module),
he will be marked as online by a green circle (CSS color). Otherwise the circle will be grey (= offline).

## Member Ranks

Based on a members' post count a title can be displayed beneath his name in the forum posts view.
The names and breakpoints for the ranks can be entered through the forum frontend module.
The displayed language of the rank is based on the frontend language of the forum (configurable in the forum frontend module).