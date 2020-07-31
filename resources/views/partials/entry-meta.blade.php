<time class="updated" datetime="{{ get_post_time('c', true) }}"><a href="{{get_the_permalink()}}">{{ get_the_date() }}</a></time>
<p class="byline author vcard"> {{ __('By', 'sage') }} <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" rel="author" class="fn"> {{ get_the_author() }}
  </a>
</p>
