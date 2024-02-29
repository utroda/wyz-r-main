/**
 * API NOTES:
    // list all recipes
    http://localhost:8888/api/recipes

    // search by ingredients
    http://localhost:8888/api/recipes/?search[ingredients][0]=Cod

    // serach by email
    http://localhost:8888/api/recipes?search[author_email]=raul.von@example.org

    // combined search
    http://localhost:8888/api/recipes?search[author_email]=raul.von@example.org&search[ingredients][0]=Sockeye
*/
export default defineEventHandler(async (event) => {
  const { page, author, ingredient } = getQuery(event);
  const queryParams = new URLSearchParams();

  if (author) {
    queryParams.set('search[author_email]', author);
  }

  if (ingredient) {
    // We could support multiple ingredients by looping here
    queryParams.set('search[ingredients][0]', ingredient);
  }

  if (page) {
    queryParams.set('page', page);
  }

  const data = await $fetch(`http://localhost/api/recipes?${queryParams.toString()}`);

  return {
    recipes: data.data,
    meta: data.meta,
  };
});
