"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
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
const express_1 = __importDefault(require("express"));
const on_init_linter_1 = __importDefault(require("./on-init-linter"));
const on_status_1 = __importDefault(require("./on-status"));
const on_analyze_jsts_1 = __importDefault(require("./on-analyze-jsts"));
const on_analyze_css_1 = __importDefault(require("./on-analyze-css"));
const on_analyze_yaml_1 = __importDefault(require("./on-analyze-yaml"));
const on_analyze_html_1 = __importDefault(require("./on-analyze-html"));
const router = express_1.default.Router();
router.post('/init-linter', on_init_linter_1.default);
router.get('/status', on_status_1.default);
router.post('/analyze-js', (0, on_analyze_jsts_1.default)('js'));
router.post('/analyze-ts', (0, on_analyze_jsts_1.default)('ts'));
router.post('/analyze-css', on_analyze_css_1.default);
router.post('/analyze-yaml', on_analyze_yaml_1.default);
router.post('/analyze-html', on_analyze_html_1.default);
exports.default = router;
//# sourceMappingURL=index.js.map