export default async ({ component: { files }, value }) => {
  const src = files.replace("/admin", "") + value + "?resize=1&w=106";

  const img = `<img style="height: 60px; width: 106px; object-fit: cover; margin-top: 6px; border-radius: 4px;" src="${src}">`;

  return Promise.resolve({ innerHTML: img });
};
