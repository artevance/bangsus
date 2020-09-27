<template>
  <div class="main-wrapper">
    <sidebar-component/>
    <div class="page-wrapper">
      <navbar-component/>
      <div class="page-content">
        <div class="d-flex justify-content-between align-items-center">
          <h3>{{ $route.meta.title }}</h3>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PerfectScrollbar from 'perfect-scrollbar'

export default {
  mounted() {
    $('.sidebar-toggler').on('click', function(e) {
      $(this).toggleClass('active');
      $(this).toggleClass('not-active');
      if (window.matchMedia('(min-width: 992px)').matches) {
        e.preventDefault();
        $('body').toggleClass('sidebar-folded');
      } else if (window.matchMedia('(max-width: 991px)').matches) {
        e.preventDefault();
        $('body').toggleClass('sidebar-open');
      }
    });

    $(".sidebar .sidebar-body").hover(
    function () {
      if ($('body').hasClass('sidebar-folded')){
        $('body').addClass("open-sidebar-folded");
      }
    },
    function () {
      if ($('body').hasClass('sidebar-folded')){
        $('body').removeClass("open-sidebar-folded");
      }
    });
    if ($('.sidebar .sidebar-body').length) {
      const sidebarBodyScroll = new PerfectScrollbar('.sidebar-body');
    }
  }
}
</script>