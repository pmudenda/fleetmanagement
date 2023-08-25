/**
 * A container of contextual information
 *
 * @param shouldUseTypeScriptParserForJS a flag for parsing JavaScript code with TypeScript ESLint parser
 * @param sonarlint a flag for indicating whether the bridge is used in SonarLint context
 * @param bundles a set of rule bundles to load
 * @param workDir the working directory of the analyzed project (used for sonar-security
 */
export interface Context {
    shouldUseTypeScriptParserForJS: boolean;
    sonarlint: boolean;
    bundles: string[];
    workDir?: string;
}
/**
 * Returns the global context
 * @returns the global context
 */
export declare function getContext(): Context;
/**
 * Sets the global context
 * @param ctx the new global context
 */
export declare function setContext(ctx: Context): void;
