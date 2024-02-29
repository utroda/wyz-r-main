<script setup>
  import { useDebounceFn } from '@vueuse/core'

  useSeoMeta({
    title: 'Recipe Browser 3000',
    description: 'Browse our collection of recipes and find your next meal.',
  })

  const observerEl = ref(null);

  const currentPage = ref(1);
  const recipeList = ref([]);
  const authorFilterInput = ref('');
  const proteinFilterInput = ref('');

  const PROTEIN_TYPES = [
    "Wild Alaskan Sockeye",
    "Wild Alaskan Coho",
    "Wild Alaskan Cod",
    "Wild Alaskan Rockfish",
    "Wild Alaska Pollock",
    "Wild Alaskan Lingcod",
    "Wild Alaskan Halibut",
    "Wild Alaskan Sablefish"
  ];

  const { data } = await useFetch(`/api/recipes`, {
    query: {
      page: currentPage,
      author: authorFilterInput,
      ingredient: proteinFilterInput,
    },
  });

  watch(data, (newData) => {
    recipeList.value = [...recipeList.value, ...data.value.recipes];
  }, { immediate: true });


  const normalizedProteinOptions = computed(() => {
    return PROTEIN_TYPES.map((protein) => ({
      label: protein,
      value: protein.split(' ').pop(),
    }));
  });

  const resetList = () => {
    currentPage.value = 1;
    recipeList.value = [];

    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  }

  // Very simple intersection observer to lazy load more recipes
  // there is shortcomings here, but it's a good starting point
  const handleIntersect = (entries) => {
    entries.forEach((entry) => {
      if (recipeList.value.length && entry.isIntersecting) {
        currentPage.value++;
      }
    });
  }

  // Debouncing to not make a request on every keystroke
  const handleAuthorFilter = useDebounceFn((event) => {
    resetList();

    const { value } = event.target;
    authorFilterInput.value = value;
  }, 500);

  const handleProteinFilter = (event) => {
    resetList();

    const { value } = event.target;
    proteinFilterInput.value = value;
  }

  // We could move this to a composable and leverage it to lazy load the images
  // on the cards as well as images at the bottom of the post
  onMounted(async () => {
    const observer = new IntersectionObserver(handleIntersect, {
      rootMargin: '0px 0px 20% 0px',
      threshold: 1,
    });

    observer.observe(observerEl.value);
  });
</script>

<template>
  <div class="page">
    <div class="filters">
      <input
        type="text"
        placeholder="Search by Author"
        @input="handleAuthorFilter"
      />
      <select @change="handleProteinFilter">
        <option value="">All Proteins</option>
        <option
          v-for="protein in normalizedProteinOptions"
          :key="protein"
          :value="protein.value"
        >
          {{ protein.label }}
        </option>
      </select>
    </div>
    <div class="no-results" v-if="!recipeList.length">
      <h2>No Results Found</h2>
      <p>Please try changing or adjusting your filters.</p>
    </div>
    <TheRecipeCard
      v-for="recipe in recipeList"
      :key="recipe.id"
      :recipe="recipe"
    />
    <div ref="observerEl" aria-hidden="true" />
  </div>
</template>

<style scoped>
  .page {
    position: relative;
    margin: 0 1rem 1rem
  }

  .filters {
    display: flex;
    position: sticky;
    top: 0px;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #ccc;
    background: #fff;
  }

  .filters input {
    padding: 0.5rem;
    border-radius: var(--border-radius);
    border: 1px solid #999;
  }

  .no-results {
    margin: 1rem 0;
    text-align: center;
  }
</style>
