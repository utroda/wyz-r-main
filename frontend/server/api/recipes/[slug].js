export default defineEventHandler(async (event) => {
  const slug = getRouterParam(event, 'slug');

  const { data } = await $fetch(`http://localhost/api/recipes/${slug}`);

  return data;
});
