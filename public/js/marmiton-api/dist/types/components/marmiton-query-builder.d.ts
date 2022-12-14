import { RECIPE_DIFFICULTY, RECIPE_PRICE, RECIPE_TYPE } from './recipe-enums';
export declare class MarmitonQueryBuilder {
    private queryString;
    withTitleContaining(q: string): this;
    withPrice(p: RECIPE_PRICE): this;
    withDifficulty(d: RECIPE_DIFFICULTY): this;
    withType(t: RECIPE_TYPE): this;
    takingLessThan(minutes: number): this;
    vegetarian(): this;
    vegan(): this;
    withoutGluten(): this;
    withoutDairyProducts(): this;
    /**
     * Without any cooking whatsoever
     */
    raw(): this;
    withoutOven(): this;
    /**
     * There must be photo of the final product.
     */
    withPhoto(): this;
    build(): string;
}
