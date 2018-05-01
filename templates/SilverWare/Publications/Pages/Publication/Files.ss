<% if $EnabledFiles %>
  <div class="files">
    <% if $HeadingText %>
      <header>
        <h4>$HeadingText</h4>
      </header>
    <% end_if %>
    <ul class="fa-ul">
      <% loop $EnabledFiles %>
        <li>
          <% include Icon Name='file-o', Size='', ListItem=1 %>
          <a href="$URL">$Name</a>
          <% if $ShowInfo %><span class="info">($Info)</span><% end_if %>
        </li>
      <% end_loop %>
    </ul>
  </div>
<% end_if %>
