import { FileType, JsTsLanguage } from 'helpers';
import { CpdToken, Issue, Metrics, SymbolHighlight, SyntaxHighlight } from 'linting/eslint';
import { AnalysisInput, AnalysisOutput } from 'services/analysis';
import { Perf } from 'services/monitoring';
/**
 *
 * A JavaScript / TypeScript analysis input
 *
 * On SonarLint and Vue projects, TSConfig-based analysis relies on an automatically
 * created TypeScript Program's instance by TypeScript ESLint parser, which leaves
 * to it the lifecycle of such an instance.
 *
 * For all other cases, analysis relies on an automatically created TypeScript Program's
 * instance based on a TSConfig to control the lifecycle of the main internal
 * data structure used by TypeScript ESLint parser for performance reasons.
 *
 * @param fileType the file type to select the proper linting configuration
 * @param language the file language ('js' or 'ts')
 * @param ignoreHeaderComments a flag used by some rules to ignore header comments
 * @param tsConfigs a list of TSConfigs
 * @param createProgram force creation of a program
 * @param forceUpdateTSConfigs force reload of tsconfigs on file system
 * @param createWildcardTSConfig used for sonarLint (or vue), when true we will create a tsconfig
 *        including all files from basedir and pass it to typescript-eslint as project
 * @param useFoundTSConfigs used for sonarLint (or vue). When true, all tsconfigs found in the
 *        fs will be passed to typescript-eslint as project.
 */
export interface JsTsAnalysisInput extends AnalysisInput {
    fileType: FileType;
    language: JsTsLanguage;
    baseDir: string;
    ignoreHeaderComments?: boolean;
    tsConfigs?: string[];
    createProgram?: boolean;
    forceUpdateTSConfigs?: boolean;
    createWildcardTSConfig?: boolean;
    useFoundTSConfigs?: boolean;
}
/**
 * A JavaScript / TypeScript analysis output
 */
export interface JsTsAnalysisOutput extends AnalysisOutput {
    issues: Issue[];
    highlights?: SyntaxHighlight[];
    highlightedSymbols?: SymbolHighlight[];
    metrics?: Metrics;
    cpdTokens?: CpdToken[];
    perf?: Perf;
    ucfgPaths?: string[];
}
