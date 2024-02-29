<script setup>
  defineProps({
    recipe: {
      type: Object,
      required: true
    }
  });
</script>

<template>
  <div class="recipe">
    <div class="recipe__image">
      <NuxtLink :to="`/recipes/${recipe.slug}`">
        <img :src="recipe.images[0]" alt="recipe.name" />
      </NuxtLink>
    </div>
    <div class="recipe__info">
      <h2>{{ recipe.name }}</h2>
      <p class="recipe__author">{{ recipe.author_email }}</p>
      <p class="recipe__protein">Made With: {{ recipe.ingredients.find(i => i.type === 'protein').name }}</p>
    </div>
    <NuxtLink :to="`/recipes/${recipe.slug}`" class="btn">View Recipe</NuxtLink>
  </div>
</template>

<style scoped>
  .recipe {
    margin-bottom: 1rem;

    border: 1px solid #ccc;
    border-radius: var(--border-radius);
    background: var(--surface-raised);
    overflow: hidden;
  }

  .recipe__image {
    display: flex;
    justify-content: center;
    align-items: center;

    width: 100%;
    max-height: 200px;
    overflow: hidden;
    margin-bottom: 1rem;
  }

  .recipe__info {
    margin-top: 1rem;
    padding: 1rem;
  }

  .recipe__author {
    font-size: 0.825rem;
    font-weight: var(--text-weight-light);
    margin-bottom: 0.5rem;
  }

  .recipe__protein {
    font-weight: var(--text-weight-strong);
    font-size: 0.825rem;
    margin-bottom: 0.5rem;
  }

  .btn {
    display: block;
    background: var(--surface-accent);
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius:0 0 var(--border-radius) var(--border-radius);
    text-align: center;
  }

  h2 {
    font-weight: var(--text-weight-strong);
    color: var(--text-color-heading);
    font-size: 1.5rem;
  }

  img {
    width: 100%;
    height: auto;
  }
</style>
