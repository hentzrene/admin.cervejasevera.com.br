<template lang="pug">
div
  grid-container(
    cols="300px",
    cols-md="300px 300px 300px",
    auto-rows="100px",
    auto-rows-md="200px",
    justify="center",
    :layout="layout",
    :layout-md="layoutMd"
  )
    template(#header)
      .item
    template(#main)
      .item
    template(#side)
      .item
    template(#banner)
      .item
    template(#side)
      .item
    template(#footer)
      .item
  br
  grid-list(col-width="200px", col-width-md="300px", row-height="200px")
    grid-item.item(col-end-sm="3", col-end-md="auto")
    .item
    .item
    .item
    .item
</template>

<script>
import GridContainer from "./../src/components/GridContainer";
import GridItem from "./../src/components/GridItem";
import GridList from "./../src/components/GridList";

export default {
  data: () => ({
    layout: [["header"], ["main"], ["side"], ["banner"], ["footer"]],
    layoutMd: [
      ["header", "header", "header"],
      ["main", "main", "side"],
      ["main", "main", "side"],
      ["banner", "banner", "side"],
      ["footer", "footer", "footer"],
    ],
  }),
  beforeCreate() {
    document.head.innerHTML =
      document.head.innerHTML +
      '<meta name=viewport content="width=device-width,initial-scale=1">';
  },
  components: {
    GridContainer,
    GridItem,
    GridList,
  },
};
</script>

<style>
body {
  margin: none;
  background: #555;
}

.item {
  height: 100%;
  width: 100%;
  background: cyan;
  border-radius: 12px;
}
.grid-area-header .item {
  background: #f89427;
}
.grid-area-main.item {
  background: #eb7171;
}
.grid-area-side .item {
  background: #eb7171;
}
.grid-area-banner .item {
  background: #e3d0e6;
}
.grid-area-footer .item {
  background: #74c575;
}
</style>
