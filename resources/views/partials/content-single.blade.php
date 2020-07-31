<article @php post_class() @endphp>
  <header>
    <div class="container">
    <h1 class="entry-title center">{!! get_the_title() !!}</h1>
    @include('partials/entry-meta')
    {!! the_post_thumbnail('full') !!}
  </header>
  <div class="entry-content container">
    @php the_content() @endphp
  </div>
  <footer> {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</div>
</article>
