/**
 * Build a new Recipe JSON from parameter.
 * This isn't actually a true builder, as it's not building a class.
 * The purpose of this "builder" is to separate info retrieval and info formatting.
 */
import { Recipe } from '../@types/recipe';
export declare class RecipeBuilder {
    private infos;
    withName(name: string): this;
    withDescription(desc: string): this;
    withUrl(url: string): this;
    withRate(rate: number): this;
    withTags(tags: string[]): this;
    withDifficulty(d: number): this;
    withBudget(b: number): this;
    withAuthor(s: string): this;
    withPeople(nb: number): this;
    withIngredients(ing: string[]): this;
    withPreparationTime(prep: number): this;
    withTotalTime(total: number): this;
    withSteps(steps: string[]): this;
    withImages(images: string[]): this;
    build(): Partial<Recipe>;
}
