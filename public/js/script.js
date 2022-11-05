import { searchRecipes, MarmitonQueryBuilder, RECIPE_PRICE, RECIPE_DIFFICULTY, Recipe } from './marmiton-api'

export default async function getRecipe() {
    const qb = new MarmitonQueryBuilder();

    const query = qb
      .withTitleContaining('soja')
      .withoutOven()
      .withPrice(RECIPE_PRICE.CHEAP)
      .takingLessThan(45)
      .withDifficulty(RECIPE_DIFFICULTY.EASY)
      .build()
    // Fetch the recipes
    const recipes= await searchRecipes(query)
   return recipes
}

