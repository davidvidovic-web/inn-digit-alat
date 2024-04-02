<script crossorigin src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
<script>
  (function() {

    var vm = new Vue({
      el: document.querySelector("#mount"),
      mounted: function() {
        console.log("Hello Vue!");
      },
    });

    console.log('executed')
  })();
</script>
<div id="mount"></div>