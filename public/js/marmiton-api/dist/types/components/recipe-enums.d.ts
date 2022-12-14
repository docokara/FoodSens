/**
 * How much money would be spent by buying all the
 * stuff required to make the recipe.
 * There doesn't seems to be a direct money equivalent.
 */
export declare enum RECIPE_PRICE {
    /**
     * Let's roll out the pasta
     */
    CHEAP = 1,
    /**
     * Your average meal.
     */
    MEDIUM = 2,
    /**
     * Lobsters and such ?
     */
    EXPENSIVE = 3
}
export declare enum RECIPE_DIFFICULTY {
    VERY_EASY = 1,
    EASY = 2,
    MEDIUM = 3,
    HARD = 4
}
export declare enum RECIPE_TYPE {
    STARTER = "entree",
    MAIN_COURSE = "platprincipal",
    DESSERT = "dessert",
    SIDE_DISH = "accompagnement",
    SAUCE = "sauce",
    BEVERAGE = "boisson",
    /**
     * Anything sugary
     */
    CANDY = "confiserie",
    /**
     * How to be a better chef.
     * This one is weird.
     */
    ADVICE = "conseil"
}
