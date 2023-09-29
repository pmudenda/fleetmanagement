<?php

namespace App\Services\BarCodes;


use App\Exceptions\InvalidDataTypeException;
use App\Exceptions\InvalidFileTypeException;
use App\Interfaces\BarCodePrint;
use GdImage;

class BarcodeGenerationService extends BarCodePrint
{
    const SPACE = "212222";
    const BACK_SLASH = "222221";
    const EXCLAIMATION = "222122";
    const HASH = "121223";
    const DOLLAR_SIGN = "121322";
    const PERCENTAGE = "131222";
    const SINGLE_QUOTE = "122312";
    const ASTERIX = "221312";
    const Q = "211331";
    const R = "231131";
    const S = "213113";
    const V = "311123";
    const T = "213311";
    const U = "213131";
    const FNC = "114311";
    const DEL = "114113";
    const TILDA = "131141";
    const PIPE = "111143";
    const FNC_2 = "411113";
    const SHIFT = "411311";
    const RIGHT_CURLY_BRACE = "111341";
    const LEFT_CURLY_BRACE = "412121";
    const LOWER_Z = "214121";
    const LOWER_Y = "212141";
    const LOWER_X = "421211";
    const LOWER_W = "421112";
    const LOWER_V = "411212";
    const UPPER_W = "311321";
    const UPPER_X = "331121";
    const UPPER_Y = "312113";
    const UPPER_Z = "312311";
    const UPPER_P = "313121";
    const UPPER_O = "133121";
    const UPPER_N = "113321";
    const UPPER_M = "113123";
    const UPPER_L = "132131";
    const UPPER_K = "112331";
    const UPPER_J = "112133";
    const UPPER_I = "231311";
    const UPPER_H = "231113";
    const UPPER_G = "211313";
    const UPPER_F = "132311";
    const E = "132113";
    const D = "112313";
    const UPPER_C = "131321";
    const UPPER_B = "131123";
    const UPPER_A = "111323";
    const AT = "232121";
    const QUESTION_MARK = "212321";
    const AMPERSAND = "122213";
    const OPENNING_BRACE = "132212";
    const CLOSING_BRACE = "221213";
    const PLUS = "231212";
    const COMMA = "112232";
    const ZERO = "123122";
    const ONE = "123221";
    const FORWARD_SLASH = "113222";
    const TWO = "223211";
    const THREE = "221132";
    const FOUR = "221231";
    const FIVE = "213212";
    const SIX = "223112";
    const SEVEN = "312131";
    const EIGHT = "311222";
    const NINE = "321122";
    const COLON = "321221";
    const SEMI_COLON = "312212";
    const EQUAL = "322211";
    const GREATER_THAN = "212123";
    const LESS_THAN = "322112";
    const PERIOD = "122231";
    const HYPHEN = "122132";
    const LOWER_A = "121124";
    const LOWER_B = "121421";
    const LOWER_C = "141122";
    const LOWER_D = "141221";
    const LOWER_E = "112214";
    const LOWER_K = "241211";
    const LOWER_M = "413111";
    const LOWER_O = "134111";
    const LOWER_R = "121241";
    const LOWER_U = "124211";
    const LOWER_L = "221114";
    const LOWER_N = "241112";
    const LOWEP = "111242";
    const LOWER_Q = "121142";
    const LOWER_T = "124112";
    const LOWER_S = "114212";
    const LOWER_J = "142211";
    const LOWER_G = "122114";
    const LOWER_H = "122411";
    const LOWER_I = "142112";
    const LOWER_F = "112412";
    const APOSTROPHE = "111422";
    const UNDER_SCORE = "111224";
    const EXPONENTION = "431111";
    const RIGHT_SQUARE_BRACKET = "221411";
    const LEFT_SQUARE_BRACKET = "332111";
    const DOUBLE_BACK_SLASH = "314111";
    const FNC_4 = "114131";
    const CODE_A = "311141";
    const START_A = "211412";
    const START_C = "211232";
    const START_B = "211214";
    const STOP = "2331112";
    const FNC_1 = "411131";
    const CODE_C = "113141";
    const START_B1 = "Start B";
    const START_C1 = "Start C";
    const CODE_C1 = "CODE C";
    const START_A1 = "Start A";
    const FNC_11 = "FNC 1";
    const FNC_41 = "FNC 4";
    const FNC_21 = "FNC 2";
    const FNC_3 = "FNC 3";
    const JPEG = '.jpeg';
    const STORAGE = 'storage/';
    protected $fileType;
    /**
     * Image file path
     *
     * @var string
     */
    protected $filepath;

    /**
     * Bar code text
     *
     * @var string
     */
    protected $text;

    /**
     * Bar code size
     *
     * @var int
     */
    protected $size;

    /**
     * Bar code orientation
     *
     * @var string
     */
    protected $orientation;

    /**
     * Bar code type
     *
     * @var string|array
     */
    protected string|array $codeType = [];

    /**
     * Bar code print [true || false]
     *
     * @var string
     */
    protected string $print;

    /**
     * Bar code sizefactor
     *
     * @var string
     */
    protected string $sizefactor;

    /**
     * Bar code string
     *
     * @var string
     */
    protected string $codeString;

    /**
     * Bar code file name
     *
     * @var string
     */
    protected string $filename;


    /**
     * Class constructor
     */
    public function __construct()
    {

    }

    /**
     * Translate the $text into barcode the correct code_type like code128
     * @throws InvalidDataTypeException
     */

    public function code128(): string
    {

        if (!is_string($this->codeType)) {
            throw new InvalidDataTypeException("Code type {$this->codeType} must be string");
        }

        if (strtolower($this->codeType) == "code128") {
            $chksum = 104;

            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(
                " " => self::SPACE,
                "!" => self::EXCLAIMATION,
                "\"" => self::BACK_SLASH,
                "#" => self::HASH,
                "$" => self::DOLLAR_SIGN,
                "%" => self::PERCENTAGE,
                "&" => self::AMPERSAND,
                "'" => self::SINGLE_QUOTE,
                "(" => self::OPENNING_BRACE,
                ")" => self::CLOSING_BRACE,
                "*" => self::ASTERIX,
                "+" => self::PLUS,
                "," => self::COMMA,
                "-" => self::HYPHEN,
                "." => self::PERIOD,
                "/" => self::FORWARD_SLASH,
                "0" => self::ZERO,
                "1" => self::ONE,
                "2" => self::TWO,
                "3" => self::THREE,
                "4" => self::FOUR,
                "5" => self::FIVE,
                "6" => self::SIX,
                "7" => self::SEVEN,
                "8" => self::EIGHT,
                "9" => self::NINE,
                ":" => self::COLON,
                ";" => self::SEMI_COLON,
                "<" => self::LESS_THAN,
                "=" => self::EQUAL,
                ">" => self::GREATER_THAN,
                "?" => self::QUESTION_MARK,
                "@" => self::AT,
                "A" => self::UPPER_A,
                "B" => self::UPPER_B,
                "C" => self::UPPER_C,
                "D" => self::D,
                "E" => self::E,
                "F" => self::UPPER_F,
                "G" => self::UPPER_G,
                "H" => self::UPPER_H,
                "I" => self::UPPER_I,
                "J" => self::UPPER_J,
                "K" => self::UPPER_K,
                "L" => self::UPPER_L,
                "M" => self::UPPER_M,
                "N" => self::UPPER_N,
                "O" => self::UPPER_O,
                "P" => self::UPPER_P,
                "Q" => self::Q,
                "R" => self::R,
                "S" => self::S,
                "T" => self::T,
                "U" => self::U,
                "V" => self::V,
                "W" => self::UPPER_W,
                "X" => self::UPPER_X,
                "Y" => self::UPPER_Y,
                "Z" => self::UPPER_Z,
                "[" => self::LEFT_SQUARE_BRACKET,
                "\\" => self::DOUBLE_BACK_SLASH,
                "]" => self::RIGHT_SQUARE_BRACKET,
                "^" => self::EXPONENTION,
                "_" => self::UNDER_SCORE,
                "\`" => self::APOSTROPHE,
                "a" => self::LOWER_A,
                "b" => self::LOWER_B,
                "c" => self::LOWER_C,
                "d" => self::LOWER_D,
                "e" => self::LOWER_E,
                "f" => self::LOWER_F,
                "g" => self::LOWER_G,
                "h" => self::LOWER_H,
                "i" => self::LOWER_I,
                "j" => self::LOWER_J,
                "k" => self::LOWER_K,
                "l" => self::LOWER_L,
                "m" => self::LOWER_M,
                "n" => self::LOWER_N,
                "o" => self::LOWER_O,
                "p" => self::LOWEP,
                "q" => self::LOWER_Q,
                "r" => self::LOWER_R,
                "s" => self::LOWER_S,
                "t" => self::LOWER_T,
                "u" => self::LOWER_U,
                "v" => self::LOWER_V,
                "w" => self::LOWER_W,
                "x" => self::LOWER_X,
                "y" => self::LOWER_Y,
                "z" => self::LOWER_Z,
                "{" => self::LEFT_CURLY_BRACE,
                "|" => self::PIPE,
                "}" => self::RIGHT_CURLY_BRACE,
                "~" => self::TILDA,
                "DEL" => self::DEL,
                self::FNC_3 => self::FNC,
                self::FNC_21 => self::FNC_2,
                "SHIFT" => self::SHIFT,
                self::CODE_C1 => self::CODE_C,
                self::FNC_41 => self::FNC_4,
                "CODE A" => self::CODE_A,
                self::FNC_11 => self::FNC_1,
                self::START_A1 => self::START_A,
                self::START_B1 => self::START_B,
                self::START_C1 => self::START_C,
                "Stop" => self::STOP
            );

            $code_keys = array_keys($code_array);

            $code_values = array_flip($code_keys);

            for ($x = 1; $x <= strlen($this->text); $x++) {

                $activeKey = substr($this->text, ($x - 1), 1);

                $this->codeString .= $code_array[$activeKey];

                $chksum = ($chksum + ($code_values[$activeKey] * $x));

            }

            $this->codeString .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $this->codeString = self::START_B . $this->codeString . self::STOP;

            return $this->codeString;

        }

        throw new InvalidDataTypeException("Invalid {$this->codeType} type");
    }

    /**
     * Translate the $text into barcode the correct code_type like code128b
     * @throws InvalidDataTypeException
     */

    public function code128b(): string
    {

        if (!is_string($this->codeType)) {

            throw new InvalidDataTypeException("Code type {$this->codeType} must be string");

        }

        if (strtolower($this->codeType) == "code128b") {

            $chksum = 104;

            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $codesArray = array(" " => self::SPACE,
                "!" => self::EXCLAIMATION,
                "\"" => self::BACK_SLASH, "#" => self::HASH,
                "$" => self::DOLLAR_SIGN, "%" => self::PERCENTAGE,
                "&" => self::AMPERSAND, "'" => self::SINGLE_QUOTE,
                "(" => self::OPENNING_BRACE, ")" => self::CLOSING_BRACE,
                "*" => self::ASTERIX, "+" => self::PLUS,
                "," => self::COMMA, "-" => self::HYPHEN, "." => self::PERIOD, "/" => self::FORWARD_SLASH,
                "0" => self::ZERO, "1" => self::ONE, "2" => self::TWO, "3" => self::THREE,
                "4" => self::FOUR, "5" => self::FIVE, "6" => self::SIX, "7" => self::SEVEN,
                "8" => self::EIGHT, "9" => self::NINE, ":" => self::COLON, ";" => self::SEMI_COLON,
                "<" => self::LESS_THAN, "=" => self::EQUAL, ">" => self::GREATER_THAN, "?" => self::QUESTION_MARK,
                "@" => self::AT, "A" => self::UPPER_A, "B" => self::UPPER_B, "C" => self::UPPER_C,
                "D" => self::D, "E" => self::E, "F" => self::UPPER_F, "G" => self::UPPER_G,
                "H" => self::UPPER_H, "I" => self::UPPER_I, "J" => self::UPPER_J, "K" => self::UPPER_K,
                "L" => self::UPPER_L, "M" => self::UPPER_M, "N" => self::UPPER_N, "O" => self::UPPER_O,
                "P" => self::UPPER_P, "Q" => self::Q, "R" => self::R, "S" => self::S,
                "T" => self::T, "U" => self::U, "V" => self::V, "W" => self::UPPER_W,
                "character" => self::UPPER_X, "Y" => self::UPPER_Y,
                "Z" => self::UPPER_Z, "[" => self::LEFT_SQUARE_BRACKET,
                "\\" => self::DOUBLE_BACK_SLASH,
                "]" => self::RIGHT_SQUARE_BRACKET, "^" => self::EXPONENTION,
                "_" => self::UNDER_SCORE,
                "\`" => self::APOSTROPHE, "a" => self::LOWER_A, "b" => self::LOWER_B, "c" => self::LOWER_C,
                "d" => self::LOWER_D, "e" => self::LOWER_E, "f" => self::LOWER_F, "g" => self::LOWER_G,
                "h" => self::LOWER_H, "i" => self::LOWER_I, "j" => self::LOWER_J, "k" => self::LOWER_K,
                "l" => self::LOWER_L, "m" => self::LOWER_M, "n" => self::LOWER_N, "o" => self::LOWER_O,
                "p" => self::LOWEP, "q" => self::LOWER_Q, "r" => self::LOWER_R, "s" => self::LOWER_S,
                "t" => self::LOWER_T, "u" => self::LOWER_U, "v" => self::LOWER_V, "w" => self::LOWER_W,
                "x" => self::LOWER_X, "y" => self::LOWER_Y, "z" => self::LOWER_Z, "{" => self::LEFT_CURLY_BRACE,
                "|" => self::PIPE, "}" => self::RIGHT_CURLY_BRACE,
                "~" => self::TILDA, "DEL" => self::DEL,
                self::FNC_3 => self::FNC, self::FNC_21 => self::FNC_2,
                "SHIFT" => self::SHIFT,
                self::CODE_C1 => self::CODE_C, self::FNC_41 => self::FNC_4,
                "CODE A" => self::CODE_A,
                self::FNC_11 => self::FNC_1,
                self::START_A1 => self::START_A, self::START_B1 => self::START_B,
                self::START_C1 => self::START_C, "Stop" => self::STOP
            );

            $codeKeys = array_keys($codesArray);

            $codeValues = array_flip($codeKeys);

            for ($character = 1; $character <= strlen($this->text); $character++) {

                $activeKey = substr($this->text, ($character - 1), 1);

                $this->codeString .= $codesArray[$activeKey];

                $chksum = ($chksum + ($codeValues[$activeKey] * $character));

            }

            $this->codeString .= $codesArray[$codeKeys[($chksum - (intval($chksum / 103) * 103))]];

            $this->codeString = self::START_B . $this->codeString . self::STOP;

            return $this->codeString;
        }

        throw new InvalidDataTypeException("Invalid {$this->codeType} type");
    }

    /**
     * Translate the $text into barcode the correct code_type like code128a
     * @throws InvalidFileTypeException
     * @throws InvalidDataTypeException
     */

    public function code128a(): string
    {

        if (!is_string($this->codeType)) {
            throw new InvalidDataTypeException("Code type {$this->codeType} must be string");
        }

        if (strtolower($this->codeType) == "code128a") {

            $chksum = 103;

            // Code 128A doesn't support lower case
            // $text = strtoupper($this->text);

            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(
                " " => self::SPACE, "!" => self::EXCLAIMATION,
                "\"" => self::BACK_SLASH, "#" => self::HASH,
                "$" => self::DOLLAR_SIGN, "%" => self::PERCENTAGE,
                "&" => self::AMPERSAND, "'" => self::SINGLE_QUOTE,
                "(" => self::OPENNING_BRACE, ")" => self::CLOSING_BRACE, "*" => self::ASTERIX, "+" => self::PLUS,
                "," => self::COMMA, "-" => self::HYPHEN, "." => self::PERIOD, "/" => self::FORWARD_SLASH,
                "0" => self::ZERO, "1" => self::ONE, "2" => self::TWO, "3" => self::THREE,
                "4" => self::FOUR, "5" => self::FIVE, "6" => self::SIX, "7" => self::SEVEN,
                "8" => self::EIGHT, "9" => self::NINE, ":" => self::COLON, ";" => self::SEMI_COLON,
                "<" => self::LESS_THAN, "=" => self::EQUAL, ">" => self::GREATER_THAN, "?" => self::QUESTION_MARK,
                "@" => self::AT, "A" => self::UPPER_A, "B" => self::UPPER_B, "C" => self::UPPER_C,
                "D" => self::D, "E" => self::E, "F" => self::UPPER_F, "G" => self::UPPER_G,
                "H" => self::UPPER_H, "I" => self::UPPER_I, "J" => self::UPPER_J, "K" => self::UPPER_K,
                "L" => self::UPPER_L, "M" => self::UPPER_M, "N" => self::UPPER_N, "O" => self::UPPER_O,
                "P" => self::UPPER_P, "Q" => self::Q, "R" => self::R, "S" => self::S,
                "T" => self::T, "U" => self::U, "V" => self::V, "W" => self::UPPER_W,
                "X" => self::UPPER_X, "Y" => self::UPPER_Y,
                "Z" => self::UPPER_Z, "[" => self::LEFT_SQUARE_BRACKET,
                "\\" => self::DOUBLE_BACK_SLASH,
                "]" => self::RIGHT_SQUARE_BRACKET,
                "^" => self::EXPONENTION, "_" => self::UNDER_SCORE,
                "NUL" => self::APOSTROPHE, "SOH" => self::LOWER_A,
                "STX" => self::LOWER_B, "ETX" => self::LOWER_C,
                "EOT" => self::LOWER_D, "ENQ" => self::LOWER_E,
                "ACK" => self::LOWER_F, "BEL" => self::LOWER_G,
                "BS" => self::LOWER_H, "HT" => self::LOWER_I, "LF" => self::LOWER_J, "VT" => self::LOWER_K,
                "FF" => self::LOWER_L, "CR" => self::LOWER_M, "SO" => self::LOWER_N, "SI" => self::LOWER_O,
                "DLE" => self::LOWEP, "DC1" => self::LOWER_Q, "DC2" => self::LOWER_R, "DC3" => self::LOWER_S,
                "DC4" => self::LOWER_T, "NAK" => self::LOWER_U, "SYN" => self::LOWER_V, "ETB" => self::LOWER_W,
                "CAN" => self::LOWER_X, "EM" => self::LOWER_Y, "SUB" => self::LOWER_Z, "ESC" => self::LEFT_CURLY_BRACE,
                "FS" => self::PIPE, "GS" => self::RIGHT_CURLY_BRACE, "RS" => self::TILDA, "US" => self::DEL,
                self::FNC_3 => self::FNC,
                self::FNC_21 => self::FNC_2,
                "SHIFT" => self::SHIFT, self::CODE_C1 => self::CODE_C,
                "CODE B" => self::FNC_4,
                self::FNC_41 => self::CODE_A,
                self::FNC_11 => self::FNC_1, self::START_A1 => self::START_A,
                self::START_B1 => self::START_B,
                self::START_C1 => self::START_C, "Stop" => self::STOP
            );
            $code_keys = array_keys($code_array);

            $code_values = array_flip($code_keys);

            for ($x = 1; $x <= strlen($this->text); $x++) {

                $activeKey = substr($this->text, ($x - 1), 1);

                $this->codeString .= $code_array[$activeKey];

                $chksum = ($chksum + ($code_values[$activeKey] * $x));

            }

            $this->codeString .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $this->codeString = self::START_A . $this->codeString . self::STOP;

            return $this->codeString;
        }

        throw new InvalidFileTypeException("Invalid {$this->codeType} type");
    }

    /**
     * Translate the $text into barcode the correct code_type like code39
     * @throws InvalidDataTypeException
     * @throws InvalidFileTypeException
     */

    public function code39(): string
    {

        if (!is_string($this->codeType)) {
            throw new InvalidDataTypeException("Code type {
            $this->codeType} must be string");
        }

        if (strtolower($this->codeType) == "code39") {

            $code_array = array("0" => "111221211", "1" => "211211112", "2" => "112211112",
                "3" => "212211111", "4" => "111221112", "5" => "211221111",
                "6" => "112221111", "7" => "111211212", "8" => "211211211",
                "9" => "112211211", "A" => "211112112", "B" => "112112112",
                "C" => "212112111", "D" => "111122112", "E" => "211122111",
                "F" => "112122111", "G" => "111112212", "H" => "211112211",
                "I" => "112112211", "J" => "111122211", "K" => "211111122",
                "L" => "112111122", "M" => "212111121", "N" => "111121122",
                "O" => "211121121", "P" => "112121121", "Q" => "111111222",
                "R" => "211111221", "S" => "112111221", "T" => "111121221",
                "U" => "221111112", "V" => "122111112", "W" => "222111111",
                "X" => "121121112", "Y" => "221121111", "Z" => "122121111",
                "-" => "121111212", "." => "221111211", " " => "122111211",
                "$" => "121212111", "/" => "121211121", "+" => "121112121",
                "%" => "111212121", "*" => "121121211"
            );
            // Convert to uppercase
            $upper_text = strtoupper($this->text);

            for ($x = 1; $x <= strlen($upper_text); $x++) {

                $this->codeString .= $code_array[substr($upper_text, ($x - 1), 1)] . "1";

            }

            $this->codeString = "1211212111" . $this->codeString . "121121211";

            return $this->codeString;
        }

        throw new InvalidFileTypeException("Invalid {$this->codeType} type");
    }

    /**
     * Translate the $text into barcode the correct code_type like code25
     * @throws InvalidDataTypeException
     * @throws InvalidFileTypeException
     */

    public function code25(): string
    {

        if (!is_string($this->codeType)) {
            throw new InvalidDataTypeException(
                "Code type {$this->codeType} must be string");
        }

        if (strtolower($this->codeType) == "code25") {

            $code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

            $code_array2 = array("3-1-1-1-3", "1-3-1-1-3", "3-3-1-1-1",
                "1-1-3-1-3", "3-1-3-1-1", "1-3-3-1-1",
                "1-1-1-3-3", "3-1-1-3-1", "1-3-1-3-1",
                "1-1-3-3-1"
            );

            return $this->generateCode25Code($code_array1, $code_array2);
        }

        throw new InvalidFileTypeException("Invalid {$this->codeType} type");

    }


    /**
     * Translate the $text into barcode the correct code_type like codabar
     * @throws InvalidDataTypeException
     * @throws InvalidFileTypeException
     */

    public function codabar(): string
    {
        if (!is_string($this->codeType)) {

            throw new InvalidDataTypeException("Code type {
            $this->codeType} must be string");

        }

        if (strtolower($this->codeType) == "codabar") {

            $code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9",
                "0", "-", "$", ":", "/", ".", "+", "A", "B", "C", "D");

            $code_array2 = array("1111221", "1112112", "2211111", "1121121",
                "2111121", "1211112", "1211211", "1221111",
                "2112111", "1111122", "1112211", "1122111",
                "2111212", "2121112", "2121211", "1121212",
                "1122121", "1212112", "1112122", "1112221"
            );

            // Convert to uppercase
            $upper_text = strtoupper($this->text);

            for ($x = 1; $x <= strlen($upper_text); $x++) {

                for ($character = 0; $character < count($code_array1); $character++) {

                    if (substr($upper_text, ($character - 1), 1)
                        == $code_array1[$character]) {
                        $this->codeString .= $code_array2[$character] . "1";
                    }
                }
            }

            $this->codeString = "11221211" . $this->codeString . "1122121";

            return $this->codeString;

        }

        throw new InvalidFileTypeException("Invalid {$this->codeType} type");
    }

    /**
     * Process bar code type factory
     *
     * @throws InvalidDataTypeException
     * @throws InvalidFileTypeException
     */

    protected function generateBarcode(): void
    {

        // Pad the edges of the barcode
        switch ($this->codeType) {
            case "code128":
                $this->code128();
                break;

            case "code128b":
                $this->code128b();
                break;

            case "code128a":
                $this->code128a();
                break;

            case "code39":
                $this->code39();
                break;

            case "code25":
                $this->code25();
                break;

            case "codabar":
                $this->codabar();
                break;

            default :

                $this->codabar();
        }
        $this->createImage();
    }

    /**
     * Create image inside barcode folder under public folder
     *
     */

    protected function createImage(): void
    {
        /**
         * filepath = Customize folder name under public
         */
        if (empty($this->filepath)) {
            // Draw barcode to the screen or save in a file
            if ($this->fileType === self::JPEG) {
                header('Content-Type: image/jpeg');
                imagejpeg($this->prepareImage(),
                    public_path('storage' . "/" . $this->filename));
            } else {
                imagepng($this->prepareImage(),
                    public_path('storage' . "/" . $this->filename));
            }

        } else {
            if (!file_exists(public_path(self::STORAGE . $this->filepath))) {
                mkdir(public_path(self::STORAGE . $this->filepath));
            }

            if ($this->fileType === self::JPEG) {
                //Storage::disk('local')->put(public_path() )
                imagejpeg($this->prepareImage(),
                    public_path(self::STORAGE . $this->filepath . "/" . $this->filename));
            } else {
                imagepng($this->prepareImage(),
                    public_path(self::STORAGE . $this->filepath . "/" . $this->filename));
            }
        }

        imagedestroy($this->prepareImage());
    }

    /**
     * Prepare image resources
     *
     * @return image resource
     */

    protected function prepareImage(): image
    {
        $code_length = 20;

        if ($this->print) {

            $text_height = 30;

        } else {

            $text_height = 0;

        }

        for ($i = 1; $i <= strlen($this->codeString); $i++) {

            $code_length = $code_length
                + (integer)(substr($this->codeString, ($i - 1), 1));

        }

        if (strtolower($this->orientation) == "horizontal") {

            $img_width = $code_length * $this->sizefactor;

            $img_height = $this->size;

        } else {

            $img_width = $this->size;

            $img_height = $code_length * $this->sizefactor;

        }

        return $this->getImageCreate($img_width, $img_height, $text_height);
    }


    public function renderBarcode($text,
        $size,
        $orientation,
        $code_type,
        $print,
        $sizeFactor,
                                  array $fileData
    ): mixed
    {
        $filename = $fileData(0);
        $filepath = $fileData(1) ?? "";
        $fileType = $fileData(2) ?? self::JPEG;
        $this->text = $text;

        $this->size = $size;

        $this->orientation = $orientation;

        $this->codeType = $code_type;

        $this->print = $print;

        $this->sizefactor = $sizeFactor;

        $this->filename = $filename;

        $this->filepath = $filepath;

        $this->fileType = $fileType;

        $this->generateBarcode();

        return $this;
    }

    /**
     * Barcode file final render in browser
     */

    public function filename($file): string
    {

        $this->filename = $file;

        if (isset($this->filepath)) {
            return $this->filepath . "/" . $this->filename;
        }

        return "deviceBarcodes/" . $this->filename . $this->fileType;
    }

    /**
     * @param array $code_array1
     * @param array $code_array2
     * @return string
     */
    public function generateCode25Code(array $code_array1, array $code_array2): string
    {
        for ($characterPosition = 1; $characterPosition <= strlen($this->text); $characterPosition++) {

            for ($character = 0; $character < count($code_array1); $character++) {

                if (substr($this->text, ($characterPosition - 1), 1)
                    == $code_array1[$character]) {
                    $temp[$characterPosition] = $code_array2[$character];
                }

            }
        }

        for ($characterPosition = 1; $characterPosition <= strlen($this->text); $characterPosition += 2) {

            if (isset($temp[$characterPosition]) && isset($temp[($characterPosition + 1)])) {

                $temp1 = explode("-", $temp[$characterPosition]);

                $temp2 = explode("-", $temp[($characterPosition + 1)]);

                for ($y = 0; $y < count($temp1); $y++) {
                    $this->codeString .= $temp1[$y] . $temp2[$y];
                }

            }
        }

        $this->codeString = "1111" . $this->codeString . "311";

        return $this->codeString;
    }

    /**
     * @param float|int $img_width
     * @param float|int $img_height
     * @param int $text_height
     * @return false|GdImage|resource
     */
    public function getImageCreate(float|int $img_width,
                                   float|int $img_height,
                                   int       $text_height): false|\GdImage|resource
    {
        $image = imagecreate($img_width, $img_height + $text_height);

        $black = imagecolorallocate($image, 0, 0, 0);

        $white = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $white);

        if ($this->print) {

            imagestring($image, 5, 31, $img_height, "Reg No. "
                . $this->text, $black);
        }

        $location = 10;

        for ($position = 1; $position <= strlen($this->codeString); $position++) {

            $cur_size = $location + (substr($this->codeString, ($position - 1), 1));

            if (strtolower($this->orientation) == "horizontal") {

                imagefilledrectangle($image,
                    $location * $this->sizefactor,
                    0,
                    $cur_size * $this->sizefactor, $img_height, ($position % 2 == 0 ? $white : $black)
                );
            } else {
                imagefilledrectangle($image,
                    0, $location
                    * $this->sizefactor, $img_width,
                    $cur_size * $this->sizefactor, ($position % 2 == 0 ? $white : $black)
                );
            }

            $location = $cur_size;
        }
        return $image;
    }

}
