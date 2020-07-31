<article @php post_class() @endphp>
    @if(has_post_thumbnail())
      <div class="image" style="background-image:url({!! get_the_post_thumbnail_url() !!})">
        &nbsp;
      </div>
    @endif
  <div>
    <header class="blog-list-header">
      <h2 class="entry-title"><a href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h2>
      @include('partials/entry-meta')
    </header>
    <div class="entry-summary">
      @php the_excerpt() @endphp
    </div>
  </div>
</article>
