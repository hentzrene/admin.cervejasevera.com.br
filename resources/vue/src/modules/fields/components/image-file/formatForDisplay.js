export default ({ component: { files }, value }) => {
  if (!value) return;

  const src = files + value + "?resize=1&w=106";

  const img = `<img style="height: 60px; width: 106px; object-fit: contain; margin-top: 6px; border-radius: 4px;" src="${src}">`;

  return { innerHTML: img };
};
