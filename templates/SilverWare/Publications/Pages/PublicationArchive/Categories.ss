<% if $VisibleCategories %>
  <div class="categories">
    <% loop $VisibleCategories %>
      <article class="category">
        <header id="$Category.URLSegment">
          <h3>$Title</h3>
        </header>
        <% if $Category.ShowContentInArchive %>
          <div class="content">
            $Category.Content
          </div>
        <% end_if %>
        <div class="publications">
          $Publications
        </div>
      </article>
    <% end_loop %>
  </div>
<% end_if %>
