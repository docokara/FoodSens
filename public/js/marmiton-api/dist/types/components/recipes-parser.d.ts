import { RecipeBuilder } from './recipe-builder';
import { Recipe } from '../@types/recipe';
import { RECIPE_DIFFICULTY, RECIPE_PRICE } from './recipe-enums';
export declare class RecipesParser {
    /** ISO 8601 Regex. The only capture groups used for a recipe should be H and M */
    private static readonly ISO_8601_REGEX;
    static parseSearchResults(dom: string, baseUrl: string): Promise<Partial<Recipe>[]>;
    static parseRecipe(dom: string, rb?: RecipeBuilder): Promise<Partial<Recipe> | undefined>;
    /**
     * Parse an ISO 8601 string and return a duration in minutes.
     * @param duration
     * @private
     */
    private static parseISO8601;
    /**
     * Converts french textual representation of a recipe budget to an enum
     * @param budget
     */
    static parseBudget(budget: string): RECIPE_PRICE;
    /**
     * Converts french textual representation of a recipe difficulty to an enum
     * @param budget
     */
    static parseDifficulty(difficulty: string): RECIPE_DIFFICULTY;
    private static selectText;
    private static getCleanText;
}
