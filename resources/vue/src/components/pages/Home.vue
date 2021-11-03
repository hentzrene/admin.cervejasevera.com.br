<template lang="pug">
v-sheet.mx-auto.pa-4.d-flex.align-center(
  color="transparent",
  max-width="960px",
  min-height="100%"
)
  loading.mx-auto(v-if="loading")
  .pa-4.white--text.font-weight-bold.primary.rounded-pill.text-body-1.mx-auto(
    v-else-if="!modules.length"
  ) Nenhum módulo adicionado.
  .mx-auto(v-else)
    grid-container.py-4(
      cols="150px 150px",
      cols-sm="repeat(4, 150px)",
      cols-lg="repeat(5, 150px)",
      gap="16px"
    )
      template(v-for="({ text, to, icon, items }, i) in nav")
        v-responsive(v-if="!items", :aspect-ratio="1", :key="i", width="150px")
          module-card(:to="to", :title="text", :icon="icon")
        grid-item(
          v-else,
          col-end="span 2",
          col-end-sm="span 4",
          col-end-lg="span 5"
        )
          .white--text.text-body-1.text-uppercase.font-weight-bold {{ text }}
          grid-container(
            cols="100px 100px 100px",
            cols-sm="repeat(5, 120px)",
            cols-lg="repeat(6, 125px)",
            gap="16px"
          )
            v-responsive(
              v-for="({ text, to, icon }, i) in items",
              :aspect-ratio="1",
              :key="i",
              width="125"
            )
              module-card(:to="to", :title="text", :icon="icon")
</template>

<script>
import Loading from "@/components/tools/Loading";
import ModuleCard from "@/components/cards/Module";

export default {
  data: () => ({ loading: true }),
  computed: {
    modules() {
      return this.$rest("modules").list;
    },
    nav() {
      let withMenu = [];
      let withoutMenu = [];

      for (let m of this.modules)
        if (m.menuId) withMenu.push(m);
        else withoutMenu.push(m);

      withMenu = withMenu.reduce((r, v) => {
        const menu = r.find(({ text }) => text === v.menuTitle);

        if (!menu) {
          r.push({
            text: v.menuTitle,
            items: [{ text: v.name, to: "/" + v.key, icon: v.icon }],
          });
        } else {
          menu.items.push({ text: v.name, to: "/" + v.key, icon: v.icon });
        }

        return r;
      }, []);

      withoutMenu = withoutMenu.map(({ name, key, icon }) => ({
        text: name,
        to: "/" + key,
        icon,
      }));

      withoutMenu = withoutMenu.sort((a, b) => a.text.localeCompare(b.text));
      withMenu = withMenu
        .map((menu) => {
          menu.items = menu.items.sort((a, b) => a.text.localeCompare(b.text));
          return menu;
        })
        .sort((a, b) => a.text.localeCompare(b.text));

      return [...withoutMenu, ...withMenu];
    },
  },
  beforeCreate() {
    this.$rest("modules")
      .get()
      .finally(() => (this.loading = false));
  },
  components: {
    Loading,
    ModuleCard,
  },
};
</script>

<style></style>
