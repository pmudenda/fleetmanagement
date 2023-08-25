import { JsTsAnalysisInput } from 'services/analysis';
/**
 * Builds an ESLint SourceCode for JavaScript / TypeScript
 *
 * This functions routes the parsing of the input based on the input language,
 * the file extension, and some contextual information.
 *
 * @param input the JavaScript / TypeScript analysis input
 * @returns the parsed source code
 */
export declare function buildSourceCode(input: JsTsAnalysisInput): import("eslint").SourceCode;
