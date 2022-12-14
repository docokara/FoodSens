import { MarmitonQueryOptions } from './@types/marmiton-query-options';
import { Recipe } from './@types/recipe';
import { MarmitonQueryBuilder } from './components/marmiton-query-builder';
import { RECIPE_DIFFICULTY, RECIPE_PRICE, RECIPE_TYPE } from './components/recipe-enums';
export declare class MarmitonError extends Error {
}
/**
 * Search for recipes within marmiton.com
 * @param qs querystring to use. This can be generated with {@link MarmitonQueryBuilder}
 * @param opt
 */
export declare function searchRecipes(qs: string, opt?: Partial<MarmitonQueryOptions>): Promise<Recipe[]>;
export { MarmitonQueryBuilder, RECIPE_PRICE, RECIPE_DIFFICULTY, RECIPE_TYPE };
export type { MarmitonQueryOptions, Recipe };
