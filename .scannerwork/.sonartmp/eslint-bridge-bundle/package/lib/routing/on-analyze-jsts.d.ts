/// <reference types="qs" />
/// <reference types="express" />
import { JsTsLanguage } from 'helpers';
/**
 * Handles JavaScript analysis requests
 */
declare const _default: (language: JsTsLanguage) => import("express").RequestHandler<import("express-serve-static-core").ParamsDictionary, any, any, import("qs").ParsedQs, Record<string, any>>;
export default _default;
