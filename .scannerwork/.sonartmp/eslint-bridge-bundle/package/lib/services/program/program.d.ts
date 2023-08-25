/**
 * This file provides an API to take control over TypeScript's Program instances
 * in the context of program-based analysis for JavaScript / TypeScript.
 *
 * A TypeScript's Program instance is used by TypeScript ESLint parser in order
 * to make available TypeScript's type checker for rules willing to use type
 * information for the sake of precision. It works similarly as using TSConfigs
 * except it gives the control over the lifecycle of this internal data structure
 * used by the parser and improves performance.
 */
import ts from 'typescript';
import { ProgramResult, ProjectTSConfigs } from 'helpers';
import { ProgramCache } from 'helpers/cache';
import { JsTsAnalysisInput } from 'services/analysis';
export declare const programCache: ProgramCache;
export declare function setDefaultTSConfigs(baseDir: string, tsConfigs: ProjectTSConfigs): void;
export declare function getDefaultTSConfigs(baseDir: string, inputTSConfigs?: string[]): ProjectTSConfigs;
/**
 * Creates or gets the proper existing TypeScript's Program containing a given source file.
 * @param input JS/TS Analysis input request
 * @param cache the LRU cache object to use as cache
 * @param tsconfigs the TSConfigs DB instance to use
 * @returns the retrieved TypeScript's Program
 */
export declare function getProgramForFile(input: JsTsAnalysisInput, cache?: ProgramCache, tsconfigs?: ProjectTSConfigs): ts.Program;
/**
 * Gets the files resolved by a TSConfig
 *
 * The resolving of the files for a given TSConfig file is done
 * by invoking TypeScript compiler.
 *
 * @param tsConfig TSConfig to parse
 * @param tsconfigContents TSConfig contents that we want to provide to TSConfig
 * @param topDir root of the project, if set we will not allow TS to search for tsconfig files
 *        above this path
 * @returns the resolved TSConfig files
 */
export declare function createProgramOptions(tsConfig: string, tsconfigContents?: string, topDir?: string): ts.CreateProgramOptions & {
    missingTsConfig: boolean;
};
/**
 * Creates a TypeScript's Program instance
 *
 * TypeScript creates a Program instance per TSConfig file. This means that one
 * needs a TSConfig to create such a program. Therefore, the function expects a
 * TSConfig as an input, parses it and uses it to create a TypeScript's Program
 * instance. The program creation delegates to TypeScript the resolving of input
 * files considered by the TSConfig as well as any project references.
 *
 * @param tsConfig the TSConfig input to create a program for
 * @param tsconfigContents TSConfig contents that we want to provide to TSConfig
 * @param topDir root of the project, if set we will not allow TS to search for
 *        dependencies above this path
 * @returns the identifier of the created TypeScript's Program along with the
 *          program itself, the resolved files, project references and a boolean
 *          'missingTsConfig' which is true when an extended tsconfig.json path
 *          was not found, which defaulted to default Typescript configuration
 */
export declare function createProgram(tsConfig: string, tsconfigContents?: string, topDir?: string): ProgramResult;
export declare function isRootNodeModules(file: string, topDir?: string): boolean;
export declare function isRoot(file: string): boolean;
