/*
 *  Copyright 2011 DataStax
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

#ifndef _PHP_PDO_CASSANDRA_H_
# define _PHP_PDO_CASSANDRA_H_

#define PHP_PDO_CASSANDRA_EXTNAME "pdo_cassandra"
#define PHP_PDO_CASSANDRA_EXTVER "0.6.0"

#ifdef __cplusplus
extern "C" {
#ifdef ZTS
# include "TSRM.h"
#endif

#include "php.h"
#include <ext/standard/php_var.h>
}
#endif

#if PHP_MAJOR_VERSION >= 7

typedef size_t        size_long_t;
typedef size_t        size_int_t;

#define Z_BVAL_P(__val)             (Z_TYPE_P(__val) == IS_TRUE ? 1 : 0)
#define y_add_next_index_string     add_next_index_string
#define y_add_assoc_stringl         add_assoc_stringl
#define y_add_assoc_string          add_assoc_string

#define Y_ZVAL_STRINGL              ZVAL_STRINGL
#define Y_ZVAL_STRING               ZVAL_STRING
#define MAKE_STD_ZVAL(p)            zval _stack_zval_##p; p = &(_stack_zval_##p)
#define ALLOC_INIT_ZVAL(p)          p = (zval *)emalloc(sizeof(zval))
#define key_size(__size)            (__size)

#else

typedef long      size_long_t;
typedef int       size_int_t;
typedef long      zend_long;

#define y_add_next_index_string(arr, str)               add_next_index_string(arr, str, 1)
#define y_add_assoc_stringl(__arg, __key, __str, __len) add_assoc_stringl(__arg, __key, __str, __len, 1)
#define y_add_assoc_string(__arg, __key, __str)         add_assoc_string(__arg, __key, __str, 1)
#define Y_ZVAL_STRINGL(_zv, _str, _len)                                 \
        do {                                                            \
            char *_s = (char *) emalloc(_len + 1);                      \
            memcpy(_s, _str, _len);                                     \
            _s[_len] = '\0';                                            \
            ZVAL_STRINGL(_zv, _s, _len, 0);                             \
        } while (0)                                                     \


#define Y_ZVAL_STRING(__zv, __str)                      ZVAL_STRING(__zv, __str, 1)
#define ZVAL_UNDEF                                      ZVAL_NULL
#define key_size(__size)                                (__size + 1)
#endif

extern zend_module_entry pdo_cassandra_module_entry;
#define phpext_pdo_cassandra_ptr &pdo_cassandra_module_entry

#endif /* _PHP_PDO_CASSANDRA_H_ */
