<script setup>
const { slug } = useRoute().params;

const { data } = useFetch(() => `/api/recipes/${slug}`);

useSeoMeta({
  title: () => data.value?.name,
  ogTitle: () => data.value?.name,
  description: () => data.value?.description,
  ogDescription: () => data.value?.description,
  ogImage: () => data.value?.images[0],
})
</script>

<template>
  <div class="posting" v-if="data">
    <div class="posting__header">
      <h1>{{ data.name }}</h1>
      <div class="posting__author">
        {{ data.author_email }}
      </div>
    </div>
    <div class="posting__content">
      <div class="posting__description">
        {{ data.description }}
      </div>
      <div class="posting__ingredients">
        <h2>Ingredients</h2>
        <ul>
          <li v-for="ingredient in data.ingredients" :key="ingredient.id">
            <template v-if="ingredient.type === 'protein'">
              <TheProteinPromo :protein="ingredient" />
            </template>
            <template v-else>
              {{ ingredient.name }}, {{ ingredient.qty }} {{ ingredient.unit }}
            </template>
          </li>
        </ul>
      </div>
      <div class="posting__steps">
        <h2>Instructions</h2>
        <ol>
          <li v-for="(step, index) in data.steps" :key="index">
            {{ step }}
          </li>
        </ol>
      </div>
      <div class="posting__images">
        <h2>Images</h2>
        <ul>
          <li v-for="image in data.images" :key="image">
            <img :src="image" alt="data.name" />
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped>
.posting {
  padding: 1rem;
}
.posting__header {
  display: flex;
  flex-direction: column;
  align-items: center;

  background-color: var(--surface-hero);
  padding: 1.5rem;
}

.posting__header h1 {
  font-weight: var(--text-weight-strong);
  color: var(--text-color-heading);
  font-size: 2rem;
  margin: 2rem 0;
}

@media (min-width: 768px) {
  .posting__header h1{
    font-size: 3.5rem;
  }
}

.posting__content {
  width: 100%;
  max-width: 37.5rem;
  margin: 0 auto;
  padding: 2rem 0;
}

h2 {
  font-weight: var(--text-weight-strong);
  font-size: 1.5rem;
  margin: 1rem 0;
}

ol {
  padding: 1.5rem;
  list-style-type: decimal;
  line-height: 1.75;
}
</style>
