"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.buildSourceCode = void 0;
/*
 * SonarQube JavaScript Plugin
 * Copyright (C) 2011-2023 SonarSource SA
 * mailto:info AT sonarsource DOT com
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
const helpers_1 = require("helpers");
const jsts_1 = require("parsing/jsts");
const program_1 = require("services/program");
/**
 * Builds an ESLint SourceCode for JavaScript / TypeScript
 *
 * This functions routes the parsing of the input based on the input language,
 * the file extension, and some contextual information.
 *
 * @param input the JavaScript / TypeScript analysis input
 * @returns the parsed source code
 */
function buildSourceCode(input) {
    const vueFile = isVueFile(input.filePath);
    if (shouldUseTypescriptParser(input.language)) {
        const options = {
            // enable logs for @typescript-eslint
            // debugLevel: true,
            filePath: input.filePath,
            parser: vueFile ? jsts_1.parsers.typescript.parser : undefined,
        };
        if (shouldCreateProgram(input)) {
            try {
                const program = (0, program_1.getProgramForFile)(input);
                options.programs = [program];
            }
            catch (error) {
                (0, helpers_1.debug)(`Failed to create program for ${input.filePath}: ${error.message}`);
            }
        }
        else {
            options.project = input.tsConfigs ? [...input.tsConfigs] : [];
            if (input.useFoundTSConfigs === true) {
                options.project.push(...(0, program_1.getDefaultTSConfigs)(input.baseDir).db.keys());
            }
            if (input.createWildcardTSConfig === true) {
                options.project.push((0, helpers_1.getWildcardTSConfig)(input.baseDir));
            }
        }
        try {
            return (0, jsts_1.parseForESLint)(input.fileContent, vueFile ? jsts_1.parsers.vuejs.parse : jsts_1.parsers.typescript.parse, (0, jsts_1.buildParserOptions)(options, false));
        }
        catch (error) {
            (0, helpers_1.debug)(`Failed to parse ${input.filePath} with TypeScript parser: ${error.message}`);
            if (input.language === 'ts' && !options.project) {
                throw error;
            }
        }
        if (options.project) {
            //try without any project
            delete options.project;
            try {
                return (0, jsts_1.parseForESLint)(input.fileContent, vueFile ? jsts_1.parsers.vuejs.parse : jsts_1.parsers.typescript.parse, (0, jsts_1.buildParserOptions)(options, false));
            }
            catch (error) {
                (0, helpers_1.debug)(`Failed to parse ${input.filePath} with TypeScript parser: ${error.message}`);
                if (input.language === 'ts') {
                    throw error;
                }
            }
        }
    }
    let moduleError;
    try {
        return (0, jsts_1.parseForESLint)(input.fileContent, vueFile ? jsts_1.parsers.vuejs.parse : jsts_1.parsers.javascript.parse, (0, jsts_1.buildParserOptions)({ parser: vueFile ? jsts_1.parsers.javascript.parser : undefined }, true));
    }
    catch (error) {
        (0, helpers_1.debug)(`Failed to parse ${input.filePath} with Javascript parser: ${error.message}`);
        if (vueFile) {
            throw error;
        }
        moduleError = error;
    }
    try {
        return (0, jsts_1.parseForESLint)(input.fileContent, jsts_1.parsers.javascript.parse, (0, jsts_1.buildParserOptions)({ sourceType: 'script' }, true));
    }
    catch (error) {
        (0, helpers_1.debug)(`Failed to parse ${input.filePath} with Javascript parser in 'script' mode: ${error.message}`);
        /**
         * We prefer displaying parsing error as module if parsing as script also failed,
         * as it is more likely that the expected source type is module.
         */
        throw moduleError;
    }
}
exports.buildSourceCode = buildSourceCode;
function shouldCreateProgram(input) {
    var _a;
    return !((_a = (0, helpers_1.getContext)()) === null || _a === void 0 ? void 0 : _a.sonarlint) && !isVueFile(input.filePath) && input.createProgram === true;
}
function shouldUseTypescriptParser(language) {
    var _a;
    return ((_a = (0, helpers_1.getContext)()) === null || _a === void 0 ? void 0 : _a.shouldUseTypeScriptParserForJS) !== false || language === 'ts';
}
function isVueFile(file) {
    return file.toLowerCase().endsWith('.vue');
}
//# sourceMappingURL=build.js.map