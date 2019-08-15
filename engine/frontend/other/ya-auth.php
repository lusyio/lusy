<script>
 var url = window.location.href;
 if (url.match('#')) {
     window.location.href = url.replace('#', '?');
 } else {
     window.close();
 }
</script>
