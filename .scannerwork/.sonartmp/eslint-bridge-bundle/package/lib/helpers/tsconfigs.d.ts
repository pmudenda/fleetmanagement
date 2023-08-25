export interface TSConfig {
    filename: string;
    contents: string;
    isFallbackTSConfig?: boolean;
}
export declare class ProjectTSConfigs {
    db: Map<string, TSConfig>;
    constructor(dir?: string, inputTSConfigs?: string[]);
    get(tsconfig: string): TSConfig | undefined;
    addInputTSConfigsToDB(tsconfigs: string[], normalized?: boolean): void;
    /**
     * Iterate over saved tsConfig returning a fake tsconfig
     * as a fallback for the given file
     *
     * @param file the JS/TS file for which the tsconfig needs to be found
     * @param tsconfigs list of tsConfigs passed in the request input, they have higher priority
     */
    iterateTSConfigs(file: string, tsconfigs?: string[]): Generator<TSConfig, void, undefined>;
    /**
     * Look for tsconfig files in a given path and its child paths.
     * node_modules is ignored
     *
     * @param dir parent folder where the search starts
     */
    tsConfigLookup(dir: string): boolean;
}
export declare const wildcardTSConfigByBaseDir: Map<string, string>;
export declare function getWildcardTSConfig(baseDir?: string): string;
