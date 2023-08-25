"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.getWildcardTSConfig = exports.wildcardTSConfigByBaseDir = exports.ProjectTSConfigs = void 0;
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
const fs_1 = __importDefault(require("fs"));
const path_1 = __importDefault(require("path"));
const files_1 = require("./files");
const debug_1 = require("./debug");
const TSCONFIG_JSON = 'tsconfig.json';
/**
 * Number of attempts to match a source file with a tsconfig in the DB. To avoid too high memory
 * consumption, after we surpass this number we will default to a fallback tsconfig
 */
const MAX_TSCONFIGS_ATTEMPTS = 3;
class ProjectTSConfigs {
    constructor(dir, inputTSConfigs) {
        this.db = new Map();
        if (inputTSConfigs === null || inputTSConfigs === void 0 ? void 0 : inputTSConfigs.length) {
            this.addInputTSConfigsToDB(inputTSConfigs);
        }
        else if (dir) {
            this.tsConfigLookup(dir);
        }
    }
    get(tsconfig) {
        return this.db.get(tsconfig);
    }
    addInputTSConfigsToDB(tsconfigs, normalized = false) {
        const normalizedInputTSConfigs = normalized
            ? tsconfigs
            : tsconfigs.map(filename => (0, files_1.toUnixPath)(filename));
        // We add the tsconfigs from the request to the DB
        for (const tsConfigPath of normalizedInputTSConfigs) {
            try {
                if (!this.db.has(tsConfigPath)) {
                    const contents = fs_1.default.readFileSync(tsConfigPath, 'utf-8');
                    this.db.set(tsConfigPath, {
                        filename: tsConfigPath,
                        contents,
                    });
                }
            }
            catch (e) {
                console.log(`ERROR Could not read ${tsConfigPath}`);
            }
        }
    }
    /**
     * Iterate over saved tsConfig returning a fake tsconfig
     * as a fallback for the given file
     *
     * @param file the JS/TS file for which the tsconfig needs to be found
     * @param tsconfigs list of tsConfigs passed in the request input, they have higher priority
     */
    *iterateTSConfigs(file, tsconfigs) {
        if (tsconfigs === null || tsconfigs === void 0 ? void 0 : tsconfigs.length) {
            tsconfigs = tsconfigs.map(filename => (0, files_1.toUnixPath)(filename));
            this.addInputTSConfigsToDB(tsconfigs, true);
        }
        yield* [...this.db.values()]
            .filter(tsconfig => {
            if (tsconfigs === null || tsconfigs === void 0 ? void 0 : tsconfigs.length) {
                return tsconfigs.includes(tsconfig.filename);
            }
            return true;
        })
            .sort((tsconfig1, tsconfig2) => {
            const tsconfig = bestTSConfigForFile(file, tsconfig1, tsconfig2);
            if (tsconfig === undefined) {
                return 0;
            }
            return tsconfig === tsconfig1 ? -1 : 1;
        })
            .filter((_, index) => index < MAX_TSCONFIGS_ATTEMPTS);
        yield {
            filename: `tsconfig-${file}.json`,
            contents: generateTSConfig([file]),
            isFallbackTSConfig: true,
        };
    }
    /**
     * Look for tsconfig files in a given path and its child paths.
     * node_modules is ignored
     *
     * @param dir parent folder where the search starts
     */
    tsConfigLookup(dir) {
        let changes = false;
        if (!dir || !fs_1.default.existsSync(dir)) {
            console.log(`ERROR Could not access project directory ${dir}`);
            throw Error(`Could not access project directory ${dir}`);
        }
        (0, debug_1.debug)(`Looking for tsconfig files in ${dir}`);
        const files = fs_1.default.readdirSync(dir, { withFileTypes: true });
        for (const file of files) {
            const filename = (0, files_1.toUnixPath)(path_1.default.join(dir, file.name));
            if (file.name !== 'node_modules' && file.name !== '.scannerwork' && file.isDirectory()) {
                if (this.tsConfigLookup(filename)) {
                    changes = true;
                }
            }
            else if (fileIsTSConfig(file.name) && !file.isDirectory()) {
                (0, debug_1.debug)(`tsconfig found: ${filename}`);
                const contents = fs_1.default.readFileSync(filename, 'utf-8');
                const existingTsConfig = this.db.get(filename);
                if (!existingTsConfig || existingTsConfig.contents !== contents) {
                    changes = true;
                }
                this.db.set(filename, {
                    filename,
                    contents,
                });
            }
        }
        return changes;
    }
}
exports.ProjectTSConfigs = ProjectTSConfigs;
function fileIsTSConfig(filename) {
    return /[tj]sconfig.json/i.exec(filename) !== null;
}
/**
 * Given a file and two TSConfig, chose the better choice. tsconfig.json name has preference.
 * Otherwise, logic is based on nearest path compared to source file.
 *
 * @param file source file for which we need a tsconfig
 * @param tsconfig1 first TSConfig instance we want to compare
 * @param tsconfig2 second TSConfig instance we want to compare
 */
function bestTSConfigForFile(file, tsconfig1, tsconfig2) {
    const filename1 = path_1.default.basename(tsconfig1.filename).toLowerCase();
    const filename2 = path_1.default.basename(tsconfig2.filename).toLowerCase();
    if (filename1 === TSCONFIG_JSON && filename2 !== TSCONFIG_JSON) {
        return tsconfig1;
    }
    else if (filename1 !== TSCONFIG_JSON && filename2 === TSCONFIG_JSON) {
        return tsconfig2;
    }
    const fileDirs = path_1.default.dirname(file).split('/');
    const tsconfig1Dirs = path_1.default.dirname(tsconfig1.filename).split('/');
    const tsconfig2Dirs = path_1.default.dirname(tsconfig2.filename).split('/');
    let relativeDepth1 = -fileDirs.length;
    let relativeDepth2 = -fileDirs.length;
    for (let i = 0; i < fileDirs.length; i++) {
        if (tsconfig1Dirs.length > i && fileDirs[i] === tsconfig1Dirs[i]) {
            relativeDepth1++;
        }
        if (tsconfig2Dirs.length > i && fileDirs[i] === tsconfig2Dirs[i]) {
            relativeDepth2++;
        }
    }
    if (relativeDepth1 === 0 && tsconfig1Dirs.length > fileDirs.length) {
        relativeDepth1 = tsconfig1Dirs.length - fileDirs.length;
    }
    if (relativeDepth2 === 0 && tsconfig2Dirs.length > fileDirs.length) {
        relativeDepth2 = tsconfig2Dirs.length - fileDirs.length;
    }
    if (relativeDepth1 === relativeDepth2) {
        if (tsconfig1Dirs.length > tsconfig2Dirs.length) {
            return tsconfig2;
        }
        else {
            return tsconfig1;
        }
    }
    else if (relativeDepth1 > relativeDepth2) {
        return relativeDepth1 <= 0 ? tsconfig1 : tsconfig2;
    }
    else {
        return relativeDepth2 <= 0 ? tsconfig2 : tsconfig1;
    }
}
function generateTSConfig(files, include) {
    const tsConfig = {
        compilerOptions: {
            allowJs: true,
            noImplicitAny: true,
        },
    };
    if (files === null || files === void 0 ? void 0 : files.length) {
        tsConfig.files = files;
    }
    if (include === null || include === void 0 ? void 0 : include.length) {
        tsConfig.include = include;
    }
    return JSON.stringify(tsConfig);
}
exports.wildcardTSConfigByBaseDir = new Map();
function getWildcardTSConfig(baseDir = '') {
    const normalizedBaseDir = (0, files_1.toUnixPath)(baseDir);
    let tsConfig = exports.wildcardTSConfigByBaseDir.get(normalizedBaseDir);
    if (!tsConfig) {
        tsConfig = (0, files_1.writeTmpFile)(generateTSConfig(undefined, [`${(0, files_1.toUnixPath)(normalizedBaseDir)}/**/*`]));
        exports.wildcardTSConfigByBaseDir.set(normalizedBaseDir, tsConfig);
    }
    return tsConfig;
}
exports.getWildcardTSConfig = getWildcardTSConfig;
//# sourceMappingURL=tsconfigs.js.map