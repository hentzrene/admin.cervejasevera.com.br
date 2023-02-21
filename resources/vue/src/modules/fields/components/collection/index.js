import Index from "./Index.vue";

export default {
  name: "collectionfield",
  component: Index,
  formatForDisplay: ({ value }) => {
    if (!value) return;

    const collection = JSON.parse(value);

    let r = "<ul style='padding: 4px 0px'>";
    for (let item of collection) {
      r += `<li>${item.length > 50 ? item.slice(0, 50) + "..." : item}</li>`;
    }

    return { innerHTML: r };
  },
};
