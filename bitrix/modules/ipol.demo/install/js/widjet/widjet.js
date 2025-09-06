function ipolWidjetController(setups) {

    var defSetups = {
        label: 'iWidjet',
        params: {}
    };

    if (typeof (setups) === 'undefined') {
        setups = {};
    }

    for (var i in defSetups) {
        if (typeof (setups[i]) === 'undefined') {
            setups[i] = defSetups[i];
        }
    }

    var label = setups.label;
    var params = setups.params;

    this.options = {
        get: function (wat) {
            return options.get(wat);
        },
        set: function (value, option) {
            options.set(value, option);
        }
    };

    this.binders = {
        add: function (callback, event) {
            bindes.addBind(callback, event);
        },
        trigger: function (event, args) {
            bindes.trigger(event, args);
        }
    };

    this.states = {
        check: function (state) {
            states.check(state);
        }
    };

    this.service = {
        cloneObj: function (obj) {
            return service.cloneObj(obj);
        },
        concatObj: function (main, sub) {
            return service.concatObj(main, sub);
        },
        isEmpty: function (stf) {
            return service.isEmpty(stf);
        },
        inArray: function (wat, arr) {
            return service.inArray(wat, arr);
        },
        loadTag: function (src, mode, callback) {
            service.loadTag(src, mode, callback);
        }
    };

    this.logger = {
        warn: function (wat) {
            return logger.warn(wat);
        },
        error: function (wat) {
            return logger.error(wat);
        },
        log: function (wat) {
            return logger.log(wat);
        }
    };

    var logger = {
        warn: function (wat) {
            if (this.check('warn')) {
                console.warn(label + ": ", wat);
            }
        },

        error: function (wat) {
            if (this.check('error')) {
                console.error(label + ": ", wat);
            }
        },

        log: function (wat) {
            if (this.check('log')) {
                if (typeof (wat) === 'object') {
                    console.log(label + ": ");
                    for (var i in wat) {
                        console.log(i, wat[i]);
                    }
                } else {
                    console.log(label + ": ", wat);
                }
            }
        },

        check: function (type) {
            var depthCheck = false;

            switch (type) {
                case 'warn'  :
                    depthCheck = options.check(true, 'showWarns');
                    break;
                case 'error' :
                    depthCheck = options.check(true, 'showErrors');
                    break;
                case 'log'   :
                    depthCheck = options.check(true, 'showLogs');
                    break;
            }

            return (
                depthCheck &&
                options.check(false, 'hideMessages')
            )
        }
    };

    var service = {
        cloneObj: function (obj) {
            var ret = false;
            if (typeof (obj) !== 'object')
                return ret;
            if (arguments.length === 1) {
                ret = {};
                for (var i in obj)
                    ret[i] = obj[i];
            } else {
                ret = [];
                for (var i in obj)
                    ret.push(obj[i]);
            }
            return ret;
        },

        concatObj: function (main, sub) {
            if (typeof (main) === 'object' && typeof (sub) === 'object')
                for (var i in sub)
                    main[i] = sub[i];
            return main;
        },

        isEmpty: function (stf) {
            var empty = true;
            if (typeof (stf) === 'object')
                for (var i in stf) {
                    empty = false;
                    break;
                }
            else
                empty = (stf);
            return empty;
        },

        inArray: function (wat, arr) {
            return arr.filter(function (item) {
                return item == wat
            }).length;
        },

        loadTag: function (src, mode, callback) {
            var loadedTag = false;
            if (typeof (mode) === 'undefined' || mode === 'script') {
                loadedTag = document.createElement('script');
                loadedTag.src = src;
                loadedTag.type = "text/javascript";
                loadedTag.language = "javascript";
            } else {
                loadedTag = document.createElement('link');
                loadedTag.href = src;
                loadedTag.rel = "stylesheet";
                loadedTag.type = "text/css";
            }
            var head = document.getElementsByTagName('head')[0];
            head.appendChild(loadedTag);
            if (typeof (callback) !== 'undefined') {
                loadedTag.onload = callback;
                loadedTag.onreadystatechange = function () {
                    if (this.readyState === 'complete' || this.readyState === 'loaded')
                        loadedTag.onload();
                };
            }
        }
    };

    var options = {
        self: this,
        options: {
            showWarns: {
                value: true,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            showErrors: {
                value: true,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            showLogs: {
                value: true,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            hideMessages: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            }
        },

        check: function (value, option, isStrict) {
            var given = this.get(option);
            if (given === null) {
                return null;
            } else {
                if (typeof (isStrict) === 'undefined') {
                    return (value === given);
                } else {
                    return (value == given);
                }
            }
        },

        get: function (wat) {
            if (typeof (this.options[wat]) !== 'undefined') {
                return this.options[wat].value;
            } else {
                logger.warn('Undefined option "' + wat + '"');
                return null;
            }
        },

        set: function (value, option) {
            if (typeof (this.options[option]) === 'undefined') {
                logger.warn('Undefined option to set : ' + option);
            } else {
                if (
                    typeof (this.options[option].check) !== 'function' ||

                    this.options[option].check.call(this.self, value)
                ) {
                    this.options[option].value = value;
                } else {
                    var subhint = (typeof (this.options[option].hint) !== 'undefined' && this.options[option].hint) ? ': ' + this.options[option].hint : false;
                    logger.warn('Incorrect setting value (' + value + ') for option ' + option + subhint);
                }
            }
        },

        iniSetter: function (values, called) {
            for (var i in options.options) {
                if (
                    options.options[i].setting === called &&
                    typeof (values[i]) !== 'undefined'
                ) {
                    options.set(values[i], i);
                }
            }
        }
    };

    var bindes = {
        events: {
            onStart: []
        },

        trigger: function (event, args) {
            if (typeof (this.events[event]) === 'undefined') {
                logger.error('Unknown event ' + event);
            } else {
                if (this.events[event].length > 0) {

                    for (var i in this.events[event]) {
                        this.events[event][i](args);
                    }
                }
            }
        },

        iniSetter: function (params) {
            for (var i in this.events) {
                if (this.events.hasOwnProperty(i)) {
                    if (typeof (params[i]) !== 'undefined') {
                        if (typeof (params[i]) === 'object') {
                            for (var j in params[i]) {
                                this.addBind(params[i][j], i);
                            }
                        } else {
                            this.addBind(params[i], i);
                        }
                    }
                }
            }
        },

        addBind: function (callback, event) {
            if (typeof (callback) === 'function') {
                this.events[event].push(callback);
            } else {
                logger.warn('The callback "' + callback + '" for ' + event + ' is not a function');
            }

        }
    };

    var states = {
        self: this,
        states: {start: {_start: false}},

        check: function (state) {
            var founded = false;
            if (state == 'dataLoaded') {
                founded = false;
            }
            for (var quenue in this.states) {
                for (var qStates in this.states[quenue]) {
                    if (qStates === state) {
                        this.states[quenue][qStates] = true;
                        founded = quenue;
                    }
                }
                if (founded)
                    break;
            }

            if (founded) {
                var ready = true;
                for (var i in this.states[founded]) {
                    if (!this.states[founded][i]) {
                        ready = false;
                        break;
                    }
                }
                if (ready) {
                    if (typeof (loaders[founded]) !== 'undefined') {
                        options.iniSetter(params, founded);
                        loaders[founded].call(this.self, params);
                    }
                }
            } else {
                if (state === 'started')
                    logger.error('No callbacks for starting');
                else
                    logger.error('Unknown state of loading: ' + state);
            }
        }
    };

    var loaders = {
        'start': function (params) {
            bindes.iniSetter(params);
            bindes.trigger('onStart');
            states.check('started');
        }
    };

    var loadingSetups = {
        'options': 'object',
        'states': 'object',
        'loaders': 'funciton',
        'stages': 'object',
        'events': 'string'
    };

    for (var i in loadingSetups) {
        if (typeof (setups[i]) !== 'undefined') {
            for (var j in setups[i]) {
                if (({}).hasOwnProperty.call(setups[i], j)) {
                    if (typeof (setups[i][j]) !== loadingSetups[i]) {
                        logger.error('Illegal ' + i + ' "' + j + '": ' + setups[i][j]);
                    } else {
                        switch (i) {
                            case 'options' :
                                options.options[j] = service.cloneObj(setups.options[j]);
                                break;
                            case 'states'  :
                                states.states[j] = service.cloneObj(setups.states[j]);
                                break;
                            case 'loaders' :
                                loaders[j] = setups.loaders[j];
                                break;
                            case 'events'  :
                                bindes.events[setups.events[j]] = [];
                                break;
                            case 'stages'  :
                                if (typeof (setups.stages[j].states) !== 'object' || typeof (setups.stages[j].function) !== 'function') {
                                    logger.error('Illegal stage "' + j + '": ' + setups[i][j]);
                                } else {
                                    states.states[j] = service.cloneObj(setups.stages[j].states);
                                    loaders[j] = setups.stages[j].function;
                                }
                                break;
                        }
                    }
                }
            }
        }
    }

    states.check('_start');
}

function IPOL_DEMO_Widjet(params) {

    if (!params.path) {
        var scriptPath = document.getElementById('IPOL_DEMO_Widjet').src;
        scriptPath = scriptPath.substring(0, scriptPath.indexOf('widjet.js')) + 'scripts/';
        params.path = scriptPath;
    }

    if (!params.servicepath) {
        params.servicepath = params.path + 'service.php';
    }

    if (!params.templatepath) {
        params.templatepath = params.path + 'template.php';
    }

    if (!params.imagesPlaceMark) {
        params.imagesPlaceMark = {
            'ISSUE_POINT': 'data:image/svg+xml;base64,PCEtLSBpY29uNjY2LmNvbSAtIE1JTExJT05TIHZlY3RvciBJQ09OUyBGUkVFIC0tPjxzdmcgaWQ9IkxheWVyXzEiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDUxMiA1MTIiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnPjxnPjxwYXRoIGQ9Im00MDIuMDUgNzUuNTAyYy04MC42NjItODAuNjYtMjExLjQ0My04MC42Ni0yOTIuMTA1IDAtODAuNjU5IDgwLjY2Mi04MC42NTkgMjExLjQ0MyAwIDI5Mi4xMDVsMTE3LjcxMSAxMTcuNzA3YzE1LjU4NiAxNS41OSA0MS4wOTggMTUuNTkgNTYuNjg4IDBsMTE3LjcwNi0xMTcuNzA3YzgwLjY1OS04MC42NjIgODAuNjU5LTIxMS40NDMgMC0yOTIuMTA1em0tMTQ2LjA1MSAyMjMuNTEyYy00Mi43ODEtLjAwMi03Ny40NTctMzQuNjc4LTc3LjQ1OS03Ny40NTcuMDAyLTQyLjc3NyAzNC42NzgtNzcuNDU5IDc3LjQ1NS03Ny40NTkgNDIuNzc5LjAwNCA3Ny40NTYgMzQuNjgyIDc3LjQ1OCA3Ny40NjEgMCA0Mi43NzctMzQuNjc5IDc3LjQ1NS03Ny40NTQgNzcuNDU1eiIgZmlsbD0iI2ZmNWI1YiIgc3R5bGU9ImZpbGw6IHJnYigyNTUsIDE5OSwgMTk5KTsiPjwvcGF0aD48L2c+PGc+PHBhdGggZD0ibTQxMi42NTkgNjQuODg5Yy00MS44NDYtNDEuODQ0LTk3LjQ4LTY0Ljg4OS0xNTYuNjU5LTY0Ljg4OS01OS4xNzggMC0xMTQuODE0IDIzLjA0NS0xNTYuNjYgNjQuODg5LTQxLjg0MyA0MS44NDUtNjQuODg3IDk3LjQ4LTY0Ljg4NyAxNTYuNjU5czIzLjA0NCAxMTQuODE0IDY0Ljg4OCAxNTYuNjU5bDExNy43MDkgMTE3LjcwNmMxMC4zNzEgMTAuMzc0IDI0LjIwNSAxNi4wODcgMzguOTUxIDE2LjA4NyAxNC43NDUgMCAyOC41NzgtNS43MTMgMzguOTUxLTE2LjA4NmwxMTcuNzA3LTExNy43MDdjNDEuODQ0LTQxLjg0NSA2NC44ODgtOTcuNDggNjQuODg4LTE1Ni42NTlzLTIzLjA0NC0xMTQuODE1LTY0Ljg4OC0xNTYuNjU5em0tMjEuMjEzIDI5Mi4xMDUtMTE3LjcwNyAxMTcuNzA3Yy00LjcwNyA0LjcwNy0xMS4wMDcgNy4yOTktMTcuNzM4IDcuMjk5LTYuNzMyIDAtMTMuMDMtMi41OTItMTcuNzM2LTcuMjk5bC0xMTcuNzEtMTE3LjcwN2MtNzQuNjgzLTc0LjY4Ni03NC42ODMtMTk2LjIwNyAwLTI3MC44OTMgMzYuMTc3LTM2LjE3NyA4NC4yOC01Ni4xMDEgMTM1LjQ0NS01Ni4xMDFzOTkuMjY4IDE5LjkyNCAxMzUuNDQ2IDU2LjEwMmM3NC42ODIgNzQuNjg1IDc0LjY4MiAxOTYuMjA3IDAgMjcwLjg5MnoiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD48cGF0aCBkPSJtMjU1Ljk5OCAxMjkuMDkxYy01MC45NzggMC05Mi40NTMgNDEuNDc3LTkyLjQ1NSA5Mi40Ni4wMDIgNTAuOTc4IDQxLjQ3OSA5Mi40NTQgOTIuNDU4IDkyLjQ1NmguMDAxYzUwLjk3OSAwIDkyLjQ1NC00MS40NzYgOTIuNDU0LTkyLjQ1Ni0uMDAyLTUwLjk3OC00MS40NzgtOTIuNDU1LTkyLjQ1OC05Mi40NnptLjAwNSAxNTQuOTE2Yy0zNC40MzktLjAwMi02Mi40NTgtMjguMDItNjIuNDYtNjIuNDU2LjAwMS0zNC40NCAyOC4wMTktNjIuNDYgNjIuNDU0LTYyLjQ2IDM0LjQzOC4wMDMgNjIuNDU3IDI4LjAyMyA2Mi40NTkgNjIuNDYxIDAgMzQuNDM3LTI4LjAxNyA2Mi40NTUtNjIuNDUzIDYyLjQ1NXoiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD48L2c+PC9nPjwvc3ZnPg==',
            'TOBACCO': 'data:image/svg+xml;base64,PCEtLSBpY29uNjY2LmNvbSAtIE1JTExJT05TIHZlY3RvciBJQ09OUyBGUkVFIC0tPjxzdmcgaWQ9IkxheWVyXzEiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDUxMiA1MTIiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnPjxnPjxwYXRoIGQ9Im00MDIuMDUgNzUuNTAyYy04MC42NjItODAuNjYtMjExLjQ0My04MC42Ni0yOTIuMTA1IDAtODAuNjU5IDgwLjY2Mi04MC42NTkgMjExLjQ0MyAwIDI5Mi4xMDVsMTE3LjcxMSAxMTcuNzA3YzE1LjU4NiAxNS41OSA0MS4wOTggMTUuNTkgNTYuNjg4IDBsMTE3LjcwNi0xMTcuNzA3YzgwLjY1OS04MC42NjIgODAuNjU5LTIxMS40NDMgMC0yOTIuMTA1em0tMTQ2LjA1MSAyMjMuNTEyYy00Mi43ODEtLjAwMi03Ny40NTctMzQuNjc4LTc3LjQ1OS03Ny40NTcuMDAyLTQyLjc3NyAzNC42NzgtNzcuNDU5IDc3LjQ1NS03Ny40NTkgNDIuNzc5LjAwNCA3Ny40NTYgMzQuNjgyIDc3LjQ1OCA3Ny40NjEgMCA0Mi43NzctMzQuNjc5IDc3LjQ1NS03Ny40NTQgNzcuNDU1eiIgZmlsbD0iI2ZmNWI1YiIgc3R5bGU9ImZpbGw6IHJnYigyNTUsIDE5OSwgMTk5KTsiPjwvcGF0aD48L2c+PGc+PHBhdGggZD0ibTQxMi42NTkgNjQuODg5Yy00MS44NDYtNDEuODQ0LTk3LjQ4LTY0Ljg4OS0xNTYuNjU5LTY0Ljg4OS01OS4xNzggMC0xMTQuODE0IDIzLjA0NS0xNTYuNjYgNjQuODg5LTQxLjg0MyA0MS44NDUtNjQuODg3IDk3LjQ4LTY0Ljg4NyAxNTYuNjU5czIzLjA0NCAxMTQuODE0IDY0Ljg4OCAxNTYuNjU5bDExNy43MDkgMTE3LjcwNmMxMC4zNzEgMTAuMzc0IDI0LjIwNSAxNi4wODcgMzguOTUxIDE2LjA4NyAxNC43NDUgMCAyOC41NzgtNS43MTMgMzguOTUxLTE2LjA4NmwxMTcuNzA3LTExNy43MDdjNDEuODQ0LTQxLjg0NSA2NC44ODgtOTcuNDggNjQuODg4LTE1Ni42NTlzLTIzLjA0NC0xMTQuODE1LTY0Ljg4OC0xNTYuNjU5em0tMjEuMjEzIDI5Mi4xMDUtMTE3LjcwNyAxMTcuNzA3Yy00LjcwNyA0LjcwNy0xMS4wMDcgNy4yOTktMTcuNzM4IDcuMjk5LTYuNzMyIDAtMTMuMDMtMi41OTItMTcuNzM2LTcuMjk5bC0xMTcuNzEtMTE3LjcwN2MtNzQuNjgzLTc0LjY4Ni03NC42ODMtMTk2LjIwNyAwLTI3MC44OTMgMzYuMTc3LTM2LjE3NyA4NC4yOC01Ni4xMDEgMTM1LjQ0NS01Ni4xMDFzOTkuMjY4IDE5LjkyNCAxMzUuNDQ2IDU2LjEwMmM3NC42ODIgNzQuNjg1IDc0LjY4MiAxOTYuMjA3IDAgMjcwLjg5MnoiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD48cGF0aCBkPSJtMjU1Ljk5OCAxMjkuMDkxYy01MC45NzggMC05Mi40NTMgNDEuNDc3LTkyLjQ1NSA5Mi40Ni4wMDIgNTAuOTc4IDQxLjQ3OSA5Mi40NTQgOTIuNDU4IDkyLjQ1NmguMDAxYzUwLjk3OSAwIDkyLjQ1NC00MS40NzYgOTIuNDU0LTkyLjQ1Ni0uMDAyLTUwLjk3OC00MS40NzgtOTIuNDU1LTkyLjQ1OC05Mi40NnptLjAwNSAxNTQuOTE2Yy0zNC40MzktLjAwMi02Mi40NTgtMjguMDItNjIuNDYtNjIuNDU2LjAwMS0zNC40NCAyOC4wMTktNjIuNDYgNjIuNDU0LTYyLjQ2IDM0LjQzOC4wMDMgNjIuNDU3IDI4LjAyMyA2Mi40NTkgNjIuNDYxIDAgMzQuNDM3LTI4LjAxNyA2Mi40NTUtNjIuNDUzIDYyLjQ1NXoiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD48L2c+PC9nPjwvc3ZnPg==',
            'POSTAMAT': 'data:image/svg+xml;base64,PCEtLSBpY29uNjY2LmNvbSAtIE1JTExJT05TIHZlY3RvciBJQ09OUyBGUkVFIC0tPjxzdmcgaWQ9IkxheWVyXzEiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDUxMiA1MTIiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnPjxnPjxwYXRoIGQ9Im00MDIuMDUgNzUuNTAyYy04MC42NjItODAuNjYtMjExLjQ0My04MC42Ni0yOTIuMTA1IDAtODAuNjU5IDgwLjY2Mi04MC42NTkgMjExLjQ0MyAwIDI5Mi4xMDVsMTE3LjcxMSAxMTcuNzA3YzE1LjU4NiAxNS41OSA0MS4wOTggMTUuNTkgNTYuNjg4IDBsMTE3LjcwNi0xMTcuNzA3YzgwLjY1OS04MC42NjIgODAuNjU5LTIxMS40NDMgMC0yOTIuMTA1em0tMTQ2LjA1MSAyMjMuNTEyYy00Mi43ODEtLjAwMi03Ny40NTctMzQuNjc4LTc3LjQ1OS03Ny40NTcuMDAyLTQyLjc3NyAzNC42NzgtNzcuNDU5IDc3LjQ1NS03Ny40NTkgNDIuNzc5LjAwNCA3Ny40NTYgMzQuNjgyIDc3LjQ1OCA3Ny40NjEgMCA0Mi43NzctMzQuNjc5IDc3LjQ1NS03Ny40NTQgNzcuNDU1eiIgZmlsbD0iI2ZmNWI1YiIgc3R5bGU9ImZpbGw6IHJnYigyNTUsIDE5OSwgMTk5KTsiPjwvcGF0aD48L2c+PGc+PHBhdGggZD0ibTQxMi42NTkgNjQuODg5Yy00MS44NDYtNDEuODQ0LTk3LjQ4LTY0Ljg4OS0xNTYuNjU5LTY0Ljg4OS01OS4xNzggMC0xMTQuODE0IDIzLjA0NS0xNTYuNjYgNjQuODg5LTQxLjg0MyA0MS44NDUtNjQuODg3IDk3LjQ4LTY0Ljg4NyAxNTYuNjU5czIzLjA0NCAxMTQuODE0IDY0Ljg4OCAxNTYuNjU5bDExNy43MDkgMTE3LjcwNmMxMC4zNzEgMTAuMzc0IDI0LjIwNSAxNi4wODcgMzguOTUxIDE2LjA4NyAxNC43NDUgMCAyOC41NzgtNS43MTMgMzguOTUxLTE2LjA4NmwxMTcuNzA3LTExNy43MDdjNDEuODQ0LTQxLjg0NSA2NC44ODgtOTcuNDggNjQuODg4LTE1Ni42NTlzLTIzLjA0NC0xMTQuODE1LTY0Ljg4OC0xNTYuNjU5em0tMjEuMjEzIDI5Mi4xMDUtMTE3LjcwNyAxMTcuNzA3Yy00LjcwNyA0LjcwNy0xMS4wMDcgNy4yOTktMTcuNzM4IDcuMjk5LTYuNzMyIDAtMTMuMDMtMi41OTItMTcuNzM2LTcuMjk5bC0xMTcuNzEtMTE3LjcwN2MtNzQuNjgzLTc0LjY4Ni03NC42ODMtMTk2LjIwNyAwLTI3MC44OTMgMzYuMTc3LTM2LjE3NyA4NC4yOC01Ni4xMDEgMTM1LjQ0NS01Ni4xMDFzOTkuMjY4IDE5LjkyNCAxMzUuNDQ2IDU2LjEwMmM3NC42ODIgNzQuNjg1IDc0LjY4MiAxOTYuMjA3IDAgMjcwLjg5MnoiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD48cGF0aCBkPSJtMjU1Ljk5OCAxMjkuMDkxYy01MC45NzggMC05Mi40NTMgNDEuNDc3LTkyLjQ1NSA5Mi40Ni4wMDIgNTAuOTc4IDQxLjQ3OSA5Mi40NTQgOTIuNDU4IDkyLjQ1NmguMDAxYzUwLjk3OSAwIDkyLjQ1NC00MS40NzYgOTIuNDU0LTkyLjQ1Ni0uMDAyLTUwLjk3OC00MS40NzgtOTIuNDU1LTkyLjQ1OC05Mi40NnptLjAwNSAxNTQuOTE2Yy0zNC40MzktLjAwMi02Mi40NTgtMjguMDItNjIuNDYtNjIuNDU2LjAwMS0zNC40NCAyOC4wMTktNjIuNDYgNjIuNDU0LTYyLjQ2IDM0LjQzOC4wMDMgNjIuNDU3IDI4LjAyMyA2Mi40NTkgNjIuNDYxIDAgMzQuNDM3LTI4LjAxNyA2Mi40NTUtNjIuNDUzIDYyLjQ1NXoiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD48L2c+PC9nPjwvc3ZnPg=='
        };
    }

    var loaders = {
        onJSPCSSLoad: function () {
            widjet.states.check('JSPCSS');
        },
        onStylesLoad: function () {
            widjet.states.check('JSPCSS');
            widjet.states.check('styles');
        },
        onIPJQLoad: function () {
            widjet.states.check('jquery')
        },
        onJSPJSLoad: function () {
            widjet.states.check('JSPJS')
        },
        onPVZLoad: function () {
            widjet.states.check('PVZ')
        },
        onDataLoad: function () {
            widjet.states.check('data')
        },
        onLANGLoad: function () {
            widjet.states.check('lang')
        },
        onDocumentLoad: function () {
            widjet.states.check('document')
        },
        onYmapsLoad: function () {
            widjet.states.check('ymaps');
        },
        onYmapsReady: function () {
            widjet.states.check('mapsReady')
        },
        onYmapsInited: function () {
            widjet.states.check('mapsInited')
        }
    };

    var widjet = new ipolWidjetController({
        label: 'IPOL_DEMO_Widjet',
        options: {
            path: {
                value: params.path,
                check: function (wat) {
                    return (typeof (wat) === 'string');
                },
                setting: 'start',
                hint: 'Value must be string (url)'
            },
            servicepath: {
                value: params.servicepath,
                check: function (wat) {
                    return (typeof (wat) === 'string');
                },
                setting: 'start',
                hint: 'Value must be string (url)'
            },
            templatepath: {
                value: params.templatepath,
                check: function (wat) {
                    return (typeof (wat) === 'string');
                },
                setting: 'start',
                hint: 'Value must be string (url)'
            },
            country: {
                value: 'all',
                check: function (wat) {
                    return (typeof (wat) === 'string');
                },
                setting: 'start',
                hint: 'Value must be string (countryname)'
            },
            lang: {
                value: 'rus',
                check: function (wat) {
                    return (typeof (wat) === 'string');
                },
                setting: 'start',
                hint: 'Value must be string (laguage name)'
            },
            link: {
                value: params.link,
                check: function (wat) {
                    return (ipjq('#' + wat).length);
                },
                setting: 'afterJquery',
                hint: 'No element whit this id to put the widjet'
            },
            defaultCity: {
                value: params.defaultCity,
                check: function (name) {
                    return (this.city.check(name) !== false);
                },
                setting: 'dataLoaded',
                hint: 'Default City wasn\'t founded'
            },
            choose: {
                value: true,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            hidecash: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            hidecard: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            popup: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            noYmaps: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            apikey: {
                value: '',
                check: function (wat) {
                    return (typeof (wat) === 'string');
                },
                setting: 'start',
                hint: 'Value must be string (apikey)'
            },
            yMapsSearch: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            yMapsSearchMark: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            showListPVZ: {
                value: params.showListPVZ,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be boolean (show list PVZ panel: true/false)'
            },
            region: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            noCitySelector: {
                value: false,
                check: function (wat) {
                    return (typeof (wat) === 'boolean');
                },
                setting: 'start',
                hint: 'Value must be bool (true / false)'
            },
            goods: {
                value: false,
                check: function (wat) {
                    if (typeof (wat) !== 'object') {
                        return false;
                    }
                    if (typeof (wat.width) !== 'undefined') {
                        return false;
                    }
                    for (var i in wat) {
                        if (
                            typeof (wat[i].length) === 'undefined' || !wat[i].length ||
                            typeof (wat[i].width) === 'undefined' || !wat[i].width ||
                            typeof (wat[i].height) === 'undefined' || !wat[i].height ||
                            typeof (wat[i].weight) === 'undefined' || !wat[i].weight
                        )
                            return false;
                    }
                    return true;
                },
                setting: 'start',
                hint: 'Value must be an array of objects of type {length:(float),width:(float),height(float),weight(float)}'
            },
        },
        events: [
            'onChoose',
            'onChooseProfile',
            'onReady',
            'onCalculate'
        ],
        stages: {
            /*
             *   when controller is ready - start loadings
             */
            'mainInit': {
                states: {
                    started: false
                },
                function: function () {
                    this.service.loadTag(this.options.get('path') + "ipjq.js", 'script', loaders.onIPJQLoad);
                    var yalang = (this.options.get('lang') == 'rus') ? 'ru_RU' : 'en_GB';
                    var yaapikey = (this.options.get('apikey')) ? "apikey=" + this.options.get('apikey') + "&" : '';
                    if (this.options.get('noYmaps')) {
                        window.setTimeout(loaders.onYmapsLoad, 500); // for success loading ymaps
                    } else {
                        this.service.loadTag("https://api-maps.yandex.ru/2.1/?" + yaapikey + "lang=" + yalang, 'script', loaders.onYmapsLoad);
                    }
                    this.service.loadTag(this.options.get('path') + 'style.css', 'link', loaders.onStylesLoad);

                }
            },
            /*
             *    when jquery is ready - load extensions and ajax-calls
             */
            'afterJquery': {
                states: {
                    jquery: false
                },
                function: function () {
                    this.service.loadTag(this.options.get('path') + 'jquery.mCustomScrollbar.concat.min.js', 'script', loaders.onJSPJSLoad);

                    DATA.city.set(this.options.get('defaultCity'));

                    ipjq.getJSON(
                        widjet.options.get('servicepath'),
                        {IPOL_DEMO_action: 'getLang', lang: this.options.get('lang')},
                        LANG.write
                    );
                    loaders.onPVZLoad();
                }
            },
            /*
             *  when ymaps's script is added and loaded
             */
            'ymapsBinder1': {
                states: {
                    ymaps: false,
                    document: false
                },
                function: function () {
                    ymaps.ready(loaders.onYmapsReady());
                }
            },
            /*
             *    waiting untill ymaps are loaded, ready, steady, go
             */
            'ymapsBinder2': {
                states: {
                    mapsReady: false
                },
                function: function () {
                    YmapsLoader();
                }
            },
            /*
             *   when everything, instead of ymaps is ready
             */
            'dataLoaded': {
                states: {
                    JSPCSS: false,
                    JSPJS: false,
                    PVZ: false,
                    styles: false,
                    lang: false
                },
                function: function () {
                    loaders.onDataLoad();
                }
            },
            /*
             *   when everything is ready
             */
            'ready': {
                states: {
                    data: false,
                    mapsInited: false
                },
                function: function () {
                    template.readyA = true;
                    template.html.loadCityList();
                    if (!widjet.popupped) {
                        widjet.finalAction();
                    } else {
                        widjet.loadedToAction = true;
                    }
                    this.binders.trigger('onReady');
                }
            }
        },
        params: params
    });

    widjet.popupped = false;
    widjet.active = false;
    widjet.loadedToAction = false;
    widjet.finalActionCalled = false;
    widjet.loaderHided = false;
    widjet.freezeZoomValue = false;
    widjet.currentMapZoom = 12;
    widjet.currentMapCenter = [];
    widjet.zeroPointError = false;

    widjet.paytypes = {};

    widjet.finalAction = function () {
        if (widjet.finalActionCalled === true) {
            return;
        }
        widjet.finalActionCalled = true;
        template.ui.afterReady();
        this.IPOL_DEMOWidgetEvents();
    };

    widjet.showLoader = function (isHard = false) {
        if (widjet.loaderHided) {
            widjet.loaderHided = false;
            if (isHard) ipjq(IDS.get('PRELOADER')).css('display', '');
            else ipjq(IDS.get('PRELOADER')).fadeIn(100);
            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search, .IPOL_DEMO-widget__sidebar, .IPOL_DEMO-widget__logo').addClass('IPOL_DEMO-widget__inaccessible');
        }
    };
    widjet.hideLoader = function () {
        if (!widjet.loaderHided) {
            widjet.loaderHided = true;
            ipjq(IDS.get('PRELOADER')).fadeOut(300);
            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search, .IPOL_DEMO-widget__sidebar, .IPOL_DEMO-widget__logo').removeClass('IPOL_DEMO-widget__inaccessible');
        }
    };

    function YmapsLoader() {
        if (typeof (widjet.incrementer) === 'undefined') {
            widjet.incrementer = 0;
        }
        if (typeof (ymaps.geocode) !== 'function') {
            if (widjet.incrementer++ > 50) {
                widjet.logger.error('Unable to load ymaps');
            } else {
                window.setTimeout(YmapsLoader, 500);
            }
        } else {
            loaders.onYmapsInited();
        }
    }

    var HTML = {
        blocks: {},
        getBlock: function (block, values) {
            if (typeof HTML.blocks[block] != 'undefined') {
                _tmpBlock = HTML.blocks[block];
                if (typeof values == 'object') {
                    for (keyVal in values) {
                        _tmpBlock = _tmpBlock.replace(new RegExp('\#' + keyVal + '\#', 'g'), values[keyVal]);
                    }
                }
                _tmpBlock = IDS.replaceAll(LANG.replaceAll(_tmpBlock));

                return _tmpBlock;
            }
            return '';

        },
        save: function (data) {
            HTML.blocks = data;
            template.html.place();
        }
    };

    var DATA = {
        regions: {
            collection: {},
            cityes: {},
            map: {}
        },
        city: {
            indexOfSome: function (findItem, ObjItem) {
                for (keyI in ObjItem) {
                    if (ObjItem[keyI] == findItem) {
                        return keyI;
                    }
                }
                return false;
            },
            collection: {},
            collectionFull: {},
            current: false,
            loaded: false,
            get: function () {
                return this.current;
            },
            set: function (intCityID) {
                this.current = intCityID;
                return intCityID;
            },

            checkCity: function (intCityID) {
                return (typeof (this.collection[intCityID]) !== 'undefined') || this.indexOfSome(intCityID, this.collection) > -1;
                // return true;
            },
            getName: function (intCityID) {
                if (this.checkCity(intCityID)) {
                    if (typeof (this.collection[intCityID]) === 'undefined') {
                        intCityID = this.indexOfSome(intCityID, this.collection);
                    }
                    return this.collection[intCityID];
                }
                return false;
            },
            getFullName: function (intCityID) {
                if (this.checkCity(intCityID)) {
                    if (typeof (this.collectionFull[intCityID]) === 'undefined') {
                        intCityID = this.indexOfSome(intCityID, this.collectionFull);
                    }
                    return this.collectionFull[intCityID];
                }
                return false;
            },
            getId: function (intCityID) {
                if (this.checkCity(intCityID)) {
                    if (typeof (this.collection[intCityID]) === 'undefined') {
                        intCityID = this.indexOfSome(intCityID, this.collection);
                    }
                    return intCityID;
                }
                return false;
            }
        },

        PVZ: {
            collection: {},
            bycoord: {},
            bycoordCur: 0,

            check: function (intCityID) {
                return (
                    DATA.city.checkCity(intCityID) &&
                    typeof (this.collection[intCityID]) !== 'undefined'
                )
            },

            getCityPVZ: function (intCityID) {
                if (typeof (this.collection[intCityID]) !== 'undefined') {
                    return this.collection[intCityID];
                } else {
                    return false;
                }
            },

            getRegionPVZ: function (intCityID) {

                if (this.check(intCityID)) {
                    let by_region = {};
                    let region = DATA.regions.cityes[intCityID];
                    let city_in_region = [];
                    city_in_region.push(...DATA.regions.map[region]);
                    if (region === 81) city_in_region.push(...DATA.regions.map[9]);
                    if (region === 9) city_in_region.push(...DATA.regions.map[81]);
                    if (region === 82) city_in_region.push(...DATA.regions.map[26]);
                    if (region === 26) city_in_region.push(...DATA.regions.map[82]);
                    city_in_region.forEach((item, i, arr) => {
                        var pvzList = DATA.PVZ.collection[item];
                        for (let code in pvzList) {
                            by_region[code] = pvzList[code];
                        }
                    });
                    return by_region;
                } else {
                    widjet.logger.error('No PVZ in city ' + intCityID);
                }
            },

            getCurrent: function () {
                if (widjet.options.get('region')) return this.getRegionPVZ(DATA.city.current);
                return this.getCityPVZ(DATA.city.current);
            }
        },

        parsePVZFile: function (data) {
            if (typeof (data.pvz) === 'undefined') {
                var sign = 'Unable to load list of PVZ : ';
                if (typeof (data.pvz) === 'undefined') {
                    for (var i in data.error) {
                        sign += data.error[i] + ", ";
                    }
                    sign = sign.substr(0, sign.length - 2);
                } else {
                    sign += 'unknown error.'
                }
                widjet.logger.error(sign);
            } else {
                if (typeof (data.pvz.REGIONS) !== 'undefined') {
                    DATA.regions.collection = data.pvz.REGIONS;
                    DATA.regions.cityes = data.pvz.CITYREG;
                    DATA.regions.map = data.pvz.REGIONSMAP;
                }

                for (var pvzCity in data.pvz.POINTS) {
                    DATA.PVZ.collection[pvzCity] = data.pvz.POINTS[pvzCity];
                    if (
                        typeof (data.pvz.CITY[pvzCity]) !== 'undefined' &&
                        typeof (DATA.city.collection[pvzCity]) === 'undefined'
                    ) {
                        DATA.city.collection[pvzCity] = data.pvz.CITY[pvzCity];
                        DATA.city.collectionFull[pvzCity] = data.pvz.CITYFULL[pvzCity];
                    }
                }

                for (var pointId in data.pvz.POINTS) {
                    var ql = data.pvz.POINTS[pointId];
                    if (typeof (DATA.PVZ.collection[ql['LOCALITY_FIAS_CODE']]) === 'undefined') {
                        DATA.PVZ.collection[ql['LOCALITY_FIAS_CODE']] = [];
                    }
                    DATA.PVZ.collection[ql['LOCALITY_FIAS_CODE']].push(data.pvz.POINTS[pointId]);
                    if (
                        typeof (data.pvz.CITY[ql['LOCALITY_FIAS_CODE']]) !== 'undefined' &&
                        typeof (DATA.city.collection[ql['LOCALITY_FIAS_CODE']]) === 'undefined'
                    ) {
                        DATA.city.collection[ql['LOCALITY_FIAS_CODE']] = data.pvz.CITY[ql['LOCALITY_FIAS_CODE']];
                        DATA.city.collectionFull[ql['LOCALITY_FIAS_CODE']] = data.pvz.CITYFULL[ql['LOCALITY_FIAS_CODE']];
                    }
                }

                loaders.onPVZLoad();
            }
        }
    };

    var CALCULATION = {
        bad: false,
        data: {
            price: 0,
            term: 0,
            tarif: false
        },
        history: [],
        defaultGabs: {length: 200, width: 300, height: 400, weight: 1000},

        binder: {},

        calculate: function (pointId) {
            if (true || typeof (this.history[pointId]) === 'undefined') {
                var mark = Date.now();
                this.history[pointId] = false;
                this.data.price = null;
                this.data.term = null;
                this.data.tarif = null;
                this.request(pointId, mark);
                this.binder[parseInt(DATA.city.current)] = {};
            } else {
                this.data.price = this.history[pointId].price;
                this.data.term = this.history[pointId].term;
                this.data.tarif = this.history[pointId].tarif;

                widjet.binders.trigger('onCalculate', {
                    profiles: widjet.service.cloneObj(CALCULATION.data),
                    city: DATA.city.current,
                    cityName: DATA.city.getName(DATA.city.current),
                    pointId: pointId
                });
            }
        },

        request: function (pointId, timestamp) {
            var data = {
                pointId: pointId,
                city: DATA.city.get()
            };

            if (typeof cargo.get()[0] !== 'undefined') {
                var cargos = cargo.get();
                data.goods = [];
                for (var i in cargos) {
                    data.goods.push(cargos[i]);
                }
            } else {
                data.goods = [this.defaultGabs];
            }

            if (typeof (timestamp) !== 'undefined') {
                data.timestamp = timestamp;
            }

            if (DATA.city.current) {
                var obRequest = {IPOL_DEMO_action: 'calcPVZ', shipment: data, filters: widjet.paytypes};
                if (widjet.calcRequestConcat && typeof (widjet.calcRequestConcat) === 'object') {
                    for (var i in widjet.calcRequestConcat) {
                        obRequest[i] = widjet.calcRequestConcat[i];
                    }
                }
                ipjq.getJSON(
                    widjet.options.get('servicepath'),
                    obRequest,
                    CALCULATION.onCalc
                );
            }
        },

        onCalc: function (answer) {
            if (typeof (answer.error) !== 'undefined') {
                CALCULATION.bad = true;
                var sign = "";
                var thisIsNorma = false;
                for (var i in answer.error) {
                    if (typeof (answer.error[i]) === 'object') {
                        for (var j in answer.error[i]) {
                            sign += answer.error[i][j].text + ' (' + answer.error[i][j].code + '), ';
                            if (answer.error[i][j].code === 3)
                                thisIsNorma = true;
                        }
                    } else {
                        if (answer.error[i] === 'No calculation') {
                            thisIsNorma = true;
                        } else {
                            sign += answer.error[i] + ', ';
                        }
                    }
                }

                if (thisIsNorma) {
                    widjet.logger.warn('Troubles while calculating: ' + sign.substring(0, sign.length - 2));
                    CALCULATION.data.price = false;
                    CALCULATION.data.term = false;
                    CALCULATION.data.tarif = false;
                } else
                    widjet.logger.error('Error while calculating: ' + sign.substring(0, sign.length - 2));
            } else {
                CALCULATION.bad = false;
                CALCULATION.data.price = answer.result.price;
                CALCULATION.data.term = answer.result.term;
                CALCULATION.data.tarif = false;
                CALCULATION.history[answer.result.pointId] = {
                    price: CALCULATION.data.price,
                    term: CALCULATION.data.term,
                    tarif: CALCULATION.data.tarif
                };
            }

            widjet.binders.trigger('onCalculate', {
                profiles: widjet.service.cloneObj(CALCULATION.data),
                city: DATA.city.current,
                cityName: DATA.city.getName(DATA.city.current),
                pointId: answer.result.pointId
            });
        }
    };

    var cargo = {
        collection: (typeof widjet.options.get('goods') === 'object') ? widjet.options.get('goods') : [],

        add: function (item) {
            if (
                typeof (item) !== 'object' ||
                typeof (item.length) === 'undefined' ||
                typeof (item.width) === 'undefined' ||
                typeof (item.height) === 'undefined' ||
                typeof (item.weight) === 'undefined'
            ) {
                widjet.logger.error('Illegal item ' + item);
            } else {
                this.collection.push({
                    length: item.length,
                    width: item.width,
                    height: item.height,
                    weight: item.weight
                });
            }
        },

        reset: function () {
            this.collection = [];
        },

        get: function () {
            return widjet.service.cloneObj(this.collection);
        }
    };

    var LANG = {
        collection: {},
        replaceAll: function (content) {
            for (langKey in this.collection) {
                content = content.replace(new RegExp('\#' + langKey + '\#', 'g'), this.collection[langKey]);
            }
            return content;
        },
        get: function (wat) {
            if (typeof (this.collection[wat]) !== 'undefined') {
                return this.collection[wat];
            } else {
                widjet.logger.warn('No lang string with key ' + wat);
                return '';
            }
        },

        write: function (data) {
            ipjq.getJSON(
                widjet.options.get('templatepath'),
                {},
                HTML.save
            );

            if (typeof (data.LANG) === 'undefined') {
                var sign = 'Unable to load land-file : ';
                if (typeof (data.error) !== 'undefined') {
                    for (var i in data.error) {
                        sign += data.error[i] + ", ";
                    }
                    sign = sign.substr(0, sign.length - 2);
                } else {
                    sign += 'unknown error.'
                }
                widjet.logger.error(sign);
            } else {
                LANG.collection = widjet.service.cloneObj(data.LANG);
                loaders.onLANGLoad();
            }
        }
    };

    var makeid = function () {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    };

    var IDS = {
        WID: makeid() + '_',
        options: {
            'MAP': 'IPOL_DEMO_map',
            'PRELOADER': 'preloader',
        },
        replaceAll: function (content) {

            for (optKey in this.options) {
                content = content.replace(new RegExp("\#" + optKey + "\#", 'g'), this.WID + this.options[optKey].replace('#', ''));
            }

            return content.replace(new RegExp("\#WID\#", 'g'), this.WID);
        },
        get: function (wat) {
            if (typeof (this.options[wat]) !== 'undefined') {
                return '#' + this.WID + this.options[wat];
            } else {
                return '#' + this.WID + wat;
            }
        }
    };

    var template = {
        readyA: false,
        html: {
            get: function () {

                return HTML.getBlock('widget', {
                    'CITY': widjet.options.get('defaultCity')
                });
            },

            makeADAPT: function () {
                if (widjet.options.get('link')) {
                    return;
                }
                var moduleH = ipjq(IDS.get('IPOL_DEMO_widget_cnt')).outerHeight();
                if (moduleH < 476) {

                } else {
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list__box, .mCustomScrollBox').css('max-height', 'auto');
                }
            },
            makeFULLSCREEN: function () {

                this.makeADAPT();
                ipjq(window).resize(this.makeADAPT());
            },
            place: function () {
                var html = this.get();

                if (widjet.options.get('link')) {
                    ipjq('#' + widjet.options.get('link')).html(html);
                } else if (widjet.options.get('popup')) {
                    widjet.popupped = true;
                    html = HTML.getBlock('popup', {WIDGET: html});
                    ipjq('body').append(html);
                    this.makeFULLSCREEN();
                } else {
                    html = ipjq(html);
                    html.css('position', 'fixed');
                    html.css('top', 0);
                    html.css('left', 0);
                    html.css('z-index', 1000);
                    ipjq('body').append(html);
                    this.makeFULLSCREEN();

                }
                if (!widjet.options.get('choose')) {
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).addClass('nochoose');
                }

                ipjq(IDS.get('sidebar')).html(HTML.getBlock('sidebar'));

                if (widjet.options.get('hidecash')) {
                    ipjq(IDS.get('butn_cash')).css('display', 'none');
                }
                if (widjet.options.get('hidecard')) {
                    ipjq(IDS.get('butn_card')).css('display', 'none');
                }

                loaders.onDocumentLoad();

                this.makeADAPT();
            },

            loadCityList: function (data) {
                ipjq(ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search')).hide();
            },

            hideMap: function () {
                ipjq(IDS.get('MAP')).css('display', 'none');
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('#IPOL_DEMO_info').css('display', 'none');
            },
        },

        controller: {
            getCity: function () {
                return DATA.city.get();
            },
            loadCity: function (doLoad) {
                template.ymaps.clearMarks();
                DATA.PVZ.collection = {};
                DATA.PVZ.bycoord = {};
                DATA.PVZ.bycoordCur = 0;
                ipjq.post(
                    widjet.options.get('servicepath'),
                    {IPOL_DEMO_action: 'getCityPVZ', cityId: DATA.city.get(), cargo: cargo.get()},
                    template.controller.parseCityPVZ
                );
            },
            parseCityPVZ: function (data) {
                data = JSON.parse(data);

                if (typeof (data.pvz.C) != 'undefined') {
                    DATA.regions.collection[data.pvz.C.C] = data.pvz.C.A;
                    DATA.regions.cityes[data.pvz.C.C] = data.pvz.C.R;
                    DATA.regions.map[data.pvz.C.R] = data.pvz.C.C;

                    data.pvz.CITY = [];
                    data.pvz.CITYREG = [];
                    data.pvz.CITYFULL = [];
                    data.pvz.CITY[data.pvz.C.C] = data.pvz.C.N;
                    data.pvz.CITYREG[data.pvz.C.C] = data.pvz.C.R;
                    data.pvz.CITYFULL[data.pvz.C.C] = data.pvz.C.F;

                    DATA.city.collection[data.pvz.C.C] = data.pvz.C.N;
                    DATA.city.collectionFull[data.pvz.C.C] = data.pvz.C.F;
                }

                //restore points
                data.pvz.POINTS = [];
                if (typeof (DATA.PVZ.collection[data.pvz.C.C]) === 'undefined') {
                    DATA.PVZ.collection[data.pvz.C.C] = [];
                }
                for (var i in data.pvz.P) {
                    var point = {
                        'POINT_GUID': data.pvz.P[i]['A'],
                        'NAME': data.pvz.P[i]['B'],
                        'TYPE': data.pvz.P[i]['C'],
                        'ADDITIONAL': data.pvz.P[i]['D'],
                        'WORK_HOURS': data.pvz.P[i]['E'],
                        'FULL_ADDRESS': data.pvz.P[i]['F'],
                        'ADDRESS_LAT': data.pvz.P[i]['G'],
                        'ADDRESS_LNG': data.pvz.P[i]['H'],
                        'CASH_ALLOWED': data.pvz.P[i]['N'],
                        'CARD_ALLOWED': data.pvz.P[i]['O'],
                        'LOCALITY_FIAS_CODE': data.pvz.C.C
                    };

                    DATA.PVZ.collection[data.pvz.C.C].push(point);
                }

                if (data.pvz.P.length == 0) {
                    alert(LANG.get('ERROR_ZERO_PVZ'));
                    console.log('Error: no points in response!');
                    widjet.zeroPointError = true;
                    widjet.close();
                } else template.ymaps.init(DATA.city.current);
            },

            selectCity: function (city) {
                if (typeof (city) === 'object') {
                    city = city.data.name;
                }
                if (typeof city === 'undefined' || !city) {
                    city = ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list ul li').not('.no-active').first().data('cityid');
                }

                DATA.city.set(city);
                template.controller.loadCity();
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-box input[type=text]').val(DATA.city.getName(city));
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list ul li').removeClass('focus').addClass('no-active').parent('ul').removeClass('open');
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-box input[type=text]')[0].blur();
            },
            putCity: function (city) {
                if (typeof (city) === 'object') {
                    city = city.data.name;
                }
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-box input[type=text]').val(DATA.city.getName(city));
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list ul li').removeClass('focus').addClass('no-active').parent('ul').removeClass('open');

            },
            updatePrices: function (obData) {
                if (template.controller.currentPVZ === obData.pointId) {
                    if (CALCULATION.data.price === false) {
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__pricePlace').html(LANG.get('NO_PAY'));
                    } else {
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__pricePlace').html(CALCULATION.data.price + ' ' + LANG.get('RUB') + ' ' + ((CALCULATION.data.term !== null) ? CALCULATION.data.term : ''));
                    }
                }
            },

            currentPVZ: false,

            calculate: function (pointId) {
                CALCULATION.calculate(pointId);
            },

            choosePVZ: function (id) {
                var PVZ = DATA.PVZ.getCurrent();
                widjet.binders.trigger('onChoose', {
                    'id': id,
                    'PVZ': PVZ[id],
                    'price': CALCULATION.data.price,
                    'term': CALCULATION.data.term,
                    'tarif': CALCULATION.data.tarif,
                    'city': DATA.city.current,
                    'cityName': DATA.city.getName(DATA.city.current)
                });
                if (!widjet.options.get('link')) {
                    // this.close();
                }
            },

            open: function () {
                if (widjet.options.get('link')) {
                    widjet.logger.error('This widjet is in non-floating mode - link is set');
                } else {
                    template.ui.open();
                }
            },

            close: function () {
                if (widjet.options.get('link')) {
                    widjet.logger.error('This widjet is in non-floating mode - link is set');
                } else {
                    template.ui.close();
                }
            }
        },

        ui: {
            currentmark: false,

            markChozenPVZ: function (event) {
                template.ymaps.selectMark(event.data.id);
            },

            choosePVZ: function (event) {
                template.controller.choosePVZ(event.data.id);
            },

            open: function () {
                ipjq(IDS.get('IPOL_DEMO_popup')).show();

                if (widjet.loadedToAction) {
                    widjet.finalAction();
                } else {
                    widjet.popupped = false;
                }
                widjet.active = true;
                if (ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list__box li').length >= 10) {
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__search-list__box").mCustomScrollbar();
                }

                /* Adjusting ymaps if city not changed and previously created map exists */
                if (typeof(template.ymaps.map.container) !== 'undefined')
                    template.ymaps.map.container.fitToViewport();
            },

            close: function () {
                widjet.active = false;
                ipjq(IDS.get('IPOL_DEMO_popup')).hide();
                DATA.city.loaded = DATA.city.current;
            },

            afterReady: function () {
                if (!(widjet.options.get('showListPVZ') === true)) {
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).addClass('nopvzlist');
                }
            }
        },

        ymaps: {
            map: false,
            readyToBlink: false,
            linker: IDS.get('MAP').replace('#', ''),

            init: function (city) {
                this.readyToBlink = false;
                if (city == false) {
                    DATA.city.set('0c5b2444-70a0-4932-980c-b4dc0d3f02b5');
                    city = '0c5b2444-70a0-4932-980c-b4dc0d3f02b5';

                    widjet.options.set(city, 'defaultCity');

                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-box input[type=text]').val(city);
                }

                var retCoords = this.makeCenterByCoords(DATA.PVZ.getCurrent());

                this.loadMap(DATA.city.current, retCoords);
            },

            makeCenterByCoords: function (arPVZ) {
                var retCoords = false;
                if (typeof (arPVZ) !== 'undefined' && !widjet.service.isEmpty(arPVZ)) {
                    retCoords = [0, 0];
                    var cnt = 0;
                    for (var i in arPVZ) {
                        if (arPVZ[i].ADDRESS_LAT && arPVZ[i].ADDRESS_LNG) {
                            retCoords [0] += parseFloat(arPVZ[i].ADDRESS_LAT);
                            retCoords [1] += parseFloat(arPVZ[i].ADDRESS_LNG);
                        }
                        cnt++
                        if (cnt > 100) {
                            break;
                        }
                    }
                    retCoords [0] = retCoords[0] / cnt;
                    retCoords [1] = retCoords[1] / cnt;
                }
                return retCoords;
            },

            loadMap: function (city, coords) {
                var self = this;
                city = DATA.city.getFullName(city);

                if (typeof DATA.PVZ.getCurrent() === 'object') {
                    if (self.map) {
                        //self.map.setZoom(10);
                        self.map.setCenter(coords);
                    }
                    self.placeMarks();
                    return;
                }

                if (widjet.options.get('apikey') || typeof (coords) === 'undefined') {
                    ymaps.geocode(city, {
                        results: 1
                    }).then(function (res) {
                        var firstGeoObject = res.geoObjects.get(0);
                        var coords = firstGeoObject.geometry.getCoordinates();
                        self._loadMap(coords);
                    });
                } else {
                    self._loadMap(coords);
                }
            },

            _loadMap: function (coords) {
                if (!self.map) {
                    self.makeMap({center: coords});
                    //self.map.setZoom(10);
                } else {
                    self.map.setCenter(coords);
                    //self.map.setZoom(10);
                }

                self.placeMarks();
            },

            makeMap: function (addInfo) {
                if (typeof (addInfo) !== 'object') {
                    addInfo = {};
                }

                template.ymaps.map = new ymaps.Map(
                    template.ymaps.linker,
                    widjet.service.concatObj({
                        zoom: 10,
                        controls: []
                    }, addInfo)
                );

                this.map.controls.add(new ymaps.control.ZoomControl(), {
                    float: 'none',
                    position: {
                        left: 12,
                        bottom: 70
                    }
                });

                if (widjet.options.get('yMapsSearch')) {
                    this.map.controls.add(new ymaps.control.SearchControl(), {
                        float: 'none',
                        position: {
                            left: 12,
                            top: 20
                        },
                        noPlacemark: !widjet.options.get('yMapsSearchMark')
                    });
                }

            },

            clearMarks: function () {
                if (typeof (this.map.geoObjects) !== 'undefined') {
                    if (typeof (this.map.geoObjects.removeAll) !== 'undefined' && false)
                        this.map.geoObjects.removeAll();
                    else {
                        do {
                            var map = this.map;
                            map.geoObjects.each(function (e) {
                                map.geoObjects.remove(e);
                            });
                        } while (map.geoObjects.getBounds());
                    }
                }
            },

            placeMarks: function () {
                var pvzList = DATA.PVZ.getCurrent();

                if (typeof pvzList !== 'object') {
                    ipjq(IDS.get('sidebar')).hide();
                } else {
                    ipjq(IDS.get('sidebar')).show();
                }

                ipjq(IDS.get('panel')).find(IDS.get('pointlist')).html('');
                ipjq(IDS.get('panel')).find(IDS.get('pointlist')).html(HTML.getBlock('panel_list'));

                _panelContent = ipjq(IDS.get('pointlist')).find('.IPOL_DEMO-widget__panel-content');

                if (typeof pvzList === 'object' && !widjet.service.isEmpty(pvzList)) {
                    template.ymaps.clusterer = new ymaps.Clusterer({
                        gridSize: 64,
                        preset: 'islands#ClusterIcons',
                        clusterIconColor: '#61BC47',
                        hasBalloon: false,
                        groupByCoordinates: false,
                        clusterDisableClickZoom: false,
                        maxZoom: 13,
                        zoomMargin: [45],
                        clusterHideIconOnBalloonOpen: false,
                        geoObjectHideIconOnBalloonOpen: false
                    });
                    geoMarks = [];
                    for (var i in pvzList) {
                        var blocked = false;
                        var type = pvzList[i].TYPE;
                        if (typeof widjet.paytypes.CARD_ALLOWED != 'undefined' && pvzList[i].CARD_ALLOWED === 'N') {
                            blocked = true; // continue
                        }

                        if (typeof widjet.paytypes.CASH_ALLOWED != 'undefined' && pvzList[i].CASH_ALLOWED === 'N') {
                            blocked = true; // continue
                        }

                        pvzList[i].placeMark = new ymaps.Placemark([pvzList[i].ADDRESS_LAT, pvzList[i].ADDRESS_LNG], {}, {
                            iconLayout: ymaps.templateLayoutFactory.createClass(
                                '<div class="IPOL_DEMO-widget__placeMark' + ((blocked) ? ' inactive' : '') + '"><img class="IPOL_DEMO-widget__placeMark_img" src="' + params.imagesPlaceMark[type] + '"/></div>'
                            ),
                            iconImageSize: [30, 43],
                            iconImageOffset: [0, 0],
                            iconShape: {
                                type: 'Rectangle',
                                coordinates: [
                                    [-20, -40], [10, 0]
                                ]
                            }
                        });
                        pvzList[i].blocked = blocked;

                        geoMarks.push(pvzList[i].placeMark);
                        pvzList[i].placeMark.link = i;

                        if (widjet.options.get('showListPVZ') === true) {
                            pvzList[i].list_block = ipjq(HTML.getBlock('point', {
                                P_NAME: pvzList[i].NAME,
                                P_ADDR: pvzList[i].FULL_ADDRESS,
                                P_TYPE: pvzList[i].ADDITIONAL
                            }));
                            pvzList[i].placeMark.listItem = pvzList[i].list_block;
                        }

                        pvzList[i].placeMark.events.add(['balloonopen', 'click'], function (metka) {
                            _prevMark = template.ui.currentmark;

                            template.ui.currentmark = metka.get('target');
                            if (typeof _prevMark == 'object') {
                                try {
                                    _prevMark.events.fire('mouseleave');
                                } catch (e) {

                                }
                            }

                            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__sidebar-burger:not(.active)').trigger('click', [{pvz: true}]);
                            template.ui.markChozenPVZ({data: {id: metka.get('target').link, link: template.ui}});
                            //pvzList[i].list_block.trigger('opendd');
                            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__panel-list").css('left', '-330px');
                            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__panel-details").css('right', '0px');
                        });

                        if (!blocked && ((widjet.options.get('showListPVZ') === true))) {
                            pvzList[i].placeMark.events.add(['mouseenter'], function (metka) {
                                var cityPvz = DATA.PVZ.getCurrent();
                                var subtype = cityPvz[metka.get('target').link];
                                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__panel-content").mCustomScrollbar("scrollTo", metka.get('target').listItem);
                                metka.get('target').listItem.addClass('IPOL_DEMO-widget__panel-list__item_active');
                                metka.get('target').options.set(
                                    {
                                        iconLayout: ymaps.templateLayoutFactory.createClass(
                                            '<div class="IPOL_DEMO-widget__placeMark chosen"><img style="" class="IPOL_DEMO-widget__placeMark_img" src="' + params.imagesPlaceMark[subtype.TYPE] + '"/></div>'
                                        )
                                    }
                                );
                            });
                            pvzList[i].placeMark.events.add(['mouseleave'], function (metka) {
                                var cityPvz = DATA.PVZ.getCurrent();
                                var subtype = cityPvz[metka.get('target').link];
                                if (template.ui.currentmark != metka.get('target')) {
                                    metka.get('target').listItem.removeClass('IPOL_DEMO-widget__panel-list__item_active');
                                    metka.get('target').options.set(
                                        {
                                            iconLayout: ymaps.templateLayoutFactory.createClass(
                                                '<div class="IPOL_DEMO-widget__placeMark"><img class="IPOL_DEMO-widget__placeMark_img" src="' + params.imagesPlaceMark[subtype.TYPE] + '"/></div>'
                                            ),
                                        }
                                    );
                                }
                            });
                        }

                        if (widjet.options.get('showListPVZ') === true) {
                            pvzList[i].list_block.mark = pvzList[i].placeMark;
                            pvzList[i].list_block.on('click', {mark: i}, function (event) {
                                pvzList[event.data.mark].placeMark.events.fire('click');
                            }).on('mouseenter', {
                                id: i,
                                ifOn: true,
                                link: template.ymaps
                            }, template.ymaps.blinkPVZ).on('mouseleave', {
                                id: i,
                                ifOn: false,
                                link: template.ymaps
                            }, template.ymaps.blinkPVZ);
                            _panelContent.append(pvzList[i].list_block);
                        }
                    }

                    template.ymaps.clusterer.add(geoMarks);
                    _bounds = template.ymaps.clusterer.getBounds();
                    if (!this.map) {
                        if (_bounds[0][0] == _bounds[1][0]) {
                            this.makeMap({center: _bounds[0]});
                            //this.map.setZoom(10);
                        } else {
                            this.makeMap({bounds: _bounds});
                            this.map.setBounds(_bounds, {
                                zoomMargin: 45,
                                checkZoomRange: true,
                                duration: 500
                            });
                            //this.map.setZoom(10);
                        }

                        template.ymaps.clearMarks();
                        this.map.geoObjects.add(template.ymaps.clusterer);
                        //widjet.hideLoader();
                    } else {
                        if (_bounds[0][0] == _bounds[1][0]) {
                            this.map.setCenter(_bounds[0]);
                            //this.map.setZoom(10);
                            template.ymaps.clearMarks();
                            this.map.geoObjects.add(template.ymaps.clusterer);
                        } else {
                            this.map.setBounds(template.ymaps.clusterer.getBounds(), {
                                zoomMargin: 45, checkZoomRange: true, duration: 10
                            }).then(
                                function () {
                                    template.ymaps.clearMarks();
                                    this.map.geoObjects.add(template.ymaps.clusterer);
                                    if (!widjet.freezeZoomValue) {
                                        this.map.setZoom(12);
                                    } else {
                                        this.map.setZoom(widjet.currentMapZoom);
                                        this.map.setCenter(widjet.currentMapCenter);
                                    }
                                    widjet.freezeZoomValue = false;
                                },
                                function () {
                                    template.ymaps.clearMarks();
                                    this.map.geoObjects.add(template.ymaps.clusterer);
                                    if (!widjet.freezeZoomValue) {
                                        this.map.setZoom(12);
                                    } else {
                                        this.map.setZoom(widjet.currentMapZoom);
                                        this.map.setCenter(widjet.currentMapCenter);
                                    }
                                    widjet.freezeZoomValue = false;
                                },
                                this
                            );
                        }
                    }
                } else {
                    template.ymaps.clearMarks();
                }
                template.ymaps.map.container.fitToViewport();
                widjet.hideLoader();

                if (widjet.options.get('showListPVZ') === true) {
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__panel-content").mCustomScrollbar();
                    if (ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list__box li').length >= 10) {
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__search-list__box").mCustomScrollbar();
                    }
                }

                this.readyToBlink = true;
            },
            makeUpCenter: function (cords) {
                var projection = this.map.options.get('projection');
                cords = this.map.converter.globalToPage(
                    projection.toGlobalPixels(
                        cords,
                        this.map.getZoom()
                    )
                );
                ww = ipjq(IDS.get('panel')).width();

                if (ipjq(IDS.get('IPOL_DEMO_widget_cnt')).width() - ww > 100) {
                    cords[0] = cords[0] + ww / 2;
                }

                cords = projection.fromGlobalPixels(
                    this.map.converter.pageToGlobal(cords), this.map.getZoom()
                );

                return cords;
            },

            selectMark: function (wat) {
                var cityPvz = DATA.PVZ.getCurrent();

                if (DATA.city.current !== cityPvz[wat].LOCALITY_FIAS_CODE) {
                    DATA.city.set(cityPvz[wat].LOCALITY_FIAS_CODE);
                    city = DATA.city.getName(cityPvz[wat].LOCALITY_FIAS_CODE);
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-box input[type=text]').val(city);
                }

                template.controller.currentPVZ = cityPvz[wat].POINT_GUID;

                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__pricePlace').html(LANG.get('COUNTING'));

                var paymentInfo = '';
                if (cityPvz[wat].CASH_ALLOWED !== 'N') {
                    paymentInfo += LANG.get('H_CASH') + '<br>';
                }
                if (cityPvz[wat].CARD_ALLOWED !== 'N') {
                    paymentInfo += LANG.get('H_CARD');
                }
                if (!paymentInfo) {
                    paymentInfo = LANG.get('L_NOPAY');
                }

                CALCULATION.calculate(cityPvz[wat].POINT_GUID);

                this.map.setCenter(template.ymaps.makeUpCenter([cityPvz[wat].ADDRESS_LAT, cityPvz[wat].ADDRESS_LNG]));

                _detailPanel = ipjq(IDS.get('panel')).find(IDS.get('detail_panel'));
                _detailPanel.html('');

                _photoHTML = '';
                if (typeof cityPvz[wat].Picture != 'undefined') {
                    for (_imgIndex in cityPvz[wat].Picture) {
                        _photoHTML += HTML.getBlock('image_c', {D_PHOTO: cityPvz[wat].Picture[_imgIndex]});
                    }
                }

                var workTime = '';
                if (typeof (cityPvz[wat].WORK_HOURS) !== 'undefined') {
                    cityPvz[wat].WORK_HOURS.forEach(function (obWH, ind) {
                        workTime += LANG.get('DAY' + ind) + '.: ' + obWH.O + ' - ' + obWH.C + '<br>';
                    });
                }

                _block = ipjq(HTML.getBlock('panel_details', paramsD = {
                    D_NAME: cityPvz[wat].NAME,
                    D_ADDR: cityPvz[wat].FULL_ADDRESS,
                    D_TIME: workTime,
                    D_DESCR: cityPvz[wat].ADDITIONAL,
                    D_PAYMENT: paymentInfo
                }));

                var blockedChoose = false;
                if (typeof widjet.paytypes.CARD_ALLOWED != 'undefined' && cityPvz[wat].CARD_ALLOWED === 'N') {
                    blockedChoose = true;
                }
                if (typeof widjet.paytypes.CASH_ALLOWED != 'undefined' && cityPvz[wat].CASH_ALLOWED === 'N') {
                    blockedChoose = true;
                }

                if (blockedChoose) {
                    _block.find(IDS.get('choose_button')).css('display', 'none');
                } else {
                    _block.find(IDS.get('choose_button')).css('display', '');
                    _block.find(IDS.get('choose_button')).on('click', {id: wat}, function (event) {
                        template.controller.choosePVZ(event.data.id);
                    });
                }

                _detailPanel.html(_block);
                _detailPanel.find('.IPOL_DEMO-widget__panel-details .IPOL_DEMO-widget__panel-content').mCustomScrollbar();
            },

            blinkPVZ: function (event) {
                if (event.data.link.readyToBlink) {
                    var cityPvz = DATA.PVZ.getCurrent();
                    if (template.ui.currentmark == cityPvz[event.data.id].placeMark || cityPvz[event.data.id].blocked) {
                        return;
                    }
                    var type = cityPvz[event.data.id].TYPE;
                    if (event.data.ifOn) {
                        event.data.link.clusterer.remove(cityPvz[event.data.id].placeMark);
                        event.data.link.map.geoObjects.add(cityPvz[event.data.id].placeMark);
                        cityPvz[event.data.id].placeMark.options.set({iconImageHref: widjet.options.get('path') + "images/" + type + "_chosen.png"});
                    } else {
                        cityPvz[event.data.id].placeMark.options.set({iconImageHref: widjet.options.get('path') + "images/" + type + ".png"});
                        event.data.link.map.geoObjects.remove(cityPvz[event.data.id].placeMark);
                        event.data.link.clusterer.add(cityPvz[event.data.id].placeMark);
                    }
                }
            },
        }
    };

    widjet.binders.add(template.controller.updatePrices, 'onCalculate');

    widjet.calcRequestConcat = false;
    widjet.setCalcRequestConcat = function (obRequest) {
        if (!obRequest || typeof (obRequest) === 'object') {
            widjet.calcRequestConcat = obRequest;
        }
    }

    widjet.resetPVZMarks = function () {
        if (typeof (template.ymaps.map.geoObjects) !== 'undefined') {
            template.ymaps.clearMarks();
            template.ymaps.placeMarks();
        }
    };

    widjet.IPOL_DEMOWidgetEvents = function () {
        ipjq('.IPOL_DEMO-widget__popup__close-btn').off('click').on('click', function (e) {
            e.preventDefault();
            template.ui.close();
            return false;
        });

        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).on('click', '.IPOL_DEMO-widget__sidebar-button', {widjet: widjet}, function (e, data) {
            _this = ipjq(this);
            var idHint = _this.attr('data-hint');
            var wid = ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(idHint).outerWidth();
            var wasActive = _this.hasClass('active');
            _this.toggleClass('active');

            if (_this.hasClass('IPOL_DEMO-widget__sidebar-button-point')) {
                widjet.paytypes = {};
                if (ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__sidebar-button-point.active').length) {
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__sidebar-button-point.active').each(function () {
                        widjet.paytypes[ipjq(this).data('mtype')] = true;
                    });
                }
                widjet.currentMapZoom = template.ymaps.map.getZoom();
                widjet.currentMapCenter = template.ymaps.map.getCenter();
                widjet.freezeZoomValue = true;
                widjet.resetPVZMarks();
            } else {
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(idHint).css({
                    right: -wid,
                    'opacity': '0'
                });

                if (_this.hasClass('IPOL_DEMO-widget__sidebar-burger')) {
                    if (!((widjet.options.get('showListPVZ') === true)) && !wasActive && (((typeof (data) == 'undefined') || (typeof (data.pvz) == 'undefined')))) {
                        _this.removeClass('active');
                        return false;
                    }

                    if (_this.hasClass('close')) {
                        _this.removeClass('close');
                    }
                    _this.toggleClass('open');
                    if (!_this.hasClass('open')) {
                        _this.addClass('close');
                    }
                    if (ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__panel').hasClass('open')) {
                        if (_this.hasClass('active')) {
                            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__panel-contacts').fadeOut(600);
                            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__panel-list, .IPOL_DEMO-widget__panel-details').fadeIn(600);
                        } else {
                            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__panel-list, .IPOL_DEMO-widget__panel-details').fadeOut(600);
                            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__panel').removeClass('open');
                        }
                    } else {
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__panel').addClass('open');
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__panel-list, .IPOL_DEMO-widget__panel-details').fadeIn(600);
                    }
                }
            }
        }).on('click', '.IPOL_DEMO-widget__choose', function () {
            ipjq(this).addClass('widget__loading');
        });
        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).on('mousemove', '.IPOL_DEMO-widget__sidebar-button', function () {
            if (!ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__panel').hasClass('open')) {
                var idHint = ipjq(this).attr('data-hint');
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(idHint).css({
                    right: '67px',
                    'opacity': '1'
                });
            }
        }).on('mouseleave', '.IPOL_DEMO-widget__sidebar-button', function () {
            var idHint = ipjq(this).attr('data-hint');
            var wid = ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(idHint).outerWidth();
            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(idHint).css({
                right: -wid,
                'opacity': '0'
            });
        }).on('hover', '.IPOL_DEMO-widget__panel-headline', function () {
            if (ipjq(this).outerWidth() <= ipjq(this).find('span').outerWidth()) {
                ipjq(this).addClass('hover-long');
            }

        }, function () {
            if (ipjq(this).hasClass('hover-long')) {
                ipjq(this).removeClass('hover-long')
            }
        });

        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__sidebar-button').each(function (index, el) {
            var idHint = ipjq(el).attr('data-hint');
            var top = (ipjq(el).outerHeight() + -ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(idHint).outerHeight()) / 2 + 62 * index;
            var wid = ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(idHint).outerWidth();
            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(idHint).css({
                'right': -wid,
                'top': top,
                'opacity': '0'
            });
        });

        ipjq(IDS.get('IPOL_DEMO_widget_cnt'))
            .on('click, opendd', ".IPOL_DEMO-widget__panel-list__item", function () {
                ipjq(this).parents(".IPOL_DEMO-widget__panel-list").css('left', '-330px');
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__panel-details").css('right', '0px');
            }).on('click', '.IPOL_DEMO-widget__panel-details__back', function () {
            ipjq(this).parents('.IPOL_DEMO-widget__panel-details').css('right', '-330px');
            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__panel-list").css('left', '0px');
        }).on('click', '.IPOL_DEMO-widget__panel-details__block-img', function () {
            var src = ipjq(this).find('img').attr('src');
            var $block = ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__photo');
            $block.find('img').attr('src', src);
            $block.addClass('active');
        }).on('click', '.IPOL_DEMO-widget__photo', function (e) {
            if (!ipjq(e.target).is('img')) {
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__photo').removeClass('active');
            }
        }).on('focusin', '.IPOL_DEMO-widget__search-box input[type=text]', function () {
            ipjq(this).val('');
            ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__delivery-type').addClass('IPOL_DEMO-widget__delivery-type_close');
            setTimeout(function () {
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list ul').addClass('open')
                    .find('li').removeClass('no-active');
            }, 1000);
        }).on('click', '.IPOL_DEMO-widget__search-list ul li', function () {
            template.controller.selectCity(ipjq(this).data('cityid'));
        }).on('keydown', '.IPOL_DEMO-widget__search-box input[type=text]', function (e) {
            var $liActive = ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list ul li:not(.no-active)');
            var $liFocus = $liActive.filter('.focus');
            if (e.keyCode === 40) {
                if ($liFocus.length == 0) {
                    $liActive.first().addClass('focus');
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__search-list__box").mCustomScrollbar('scrollTo', $liActive.first(), {
                        scrollInertia: 300
                    });
                } else {
                    $liFocus.removeClass('focus');

                    if ($liFocus.nextAll().filter(':not(.no-active)').eq(0).length != 0) {
                        $liFocus.nextAll().filter(':not(.no-active)').eq(0).addClass('focus');
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__search-list__box").mCustomScrollbar('scrollTo', $liFocus.next($liActive), {
                            scrollInertia: 300
                        });
                    } else {
                        $liActive.first().addClass('focus');
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__search-list__box").mCustomScrollbar('scrollTo', $liActive.first(), {
                            scrollInertia: 300
                        });
                    }
                }
            }
            if (e.keyCode === 38) {
                if ($liFocus.length == 0) {
                    $liActive.last().addClass('focus');
                    ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__search-list__box").mCustomScrollbar('scrollTo', $liActive.last(), {
                        scrollInertia: 300
                    });
                } else {
                    $liFocus.removeClass('focus');
                    if ($liFocus.prevAll().filter(':not(.no-active)').eq(0).length != 0) {
                        $liFocus.prevAll().filter(':not(.no-active)').eq(0).addClass('focus');
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__search-list__box").mCustomScrollbar('scrollTo', $liFocus.prev($liActive), {
                            scrollInertia: 300
                        });
                    } else {
                        $liActive.last().addClass('focus');
                        ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find(".IPOL_DEMO-widget__search-list__box").mCustomScrollbar('scrollTo', $liActive.last(), {
                            scrollInertia: 300
                        });
                    }
                }
            }
        }).on('keyup', '.IPOL_DEMO-widget__search-box input[type=text]', function (e) {
            try {
                var filter = new RegExp('^(' + ipjq(this).val() + ')+.*', 'i');
            } catch (e) {
                var filter = '';
            }

            var $li = ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list ul li');

            if (e.keyCode === 13) {
                var $liActive = $li.not('.no-active');
                var $liFocus = $liActive.filter('.focus');
                if ($liFocus.length == 0) {
                    template.controller.selectCity();
                } else {
                    template.controller.selectCity($liFocus.find('.IPOL_DEMO-widget__search-list__city-name').text());
                    $liFocus.removeClass('focus');
                }
                return
            }

            if (filter != '') {
                $matches = $li.filter(function () {
                    return filter.test(ipjq(this).find('.IPOL_DEMO-widget__search-list__city-name').text().replace(/[^\wа-яё\s-]+/gi, ""));
                });

                $li.not($matches).addClass('no-active').removeClass('focus');
                if ($matches.length == 0) {
                    $li.parent('ul').removeClass('open');
                } else if (!$li.parent('ul').hasClass('open')) {
                    $li.parent('ul').addClass('open');
                }

                $matches.each(function (index, el) {
                    if (ipjq(el).hasClass('no-active')) {
                        ipjq(el).removeClass('no-active');
                    }
                });
            } else {
                $li.removeClass('no-active');
            }
        }).on('click', function (e) {
            if (ipjq(e.target).closest('.IPOL_DEMO-widget__search').length == 0 && ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__search-list ul li').not('.no-active').length != 0) {
                template.controller.putCity(template.controller.getCity());
            }
        });
    };

    widjet.city = {
        get: function () {
            return DATA.city.current
        },
        set: function (name) {
            DATA.city.set(name);
            widjet.zeroPointError = false;
            //template.controller.loadCity();
        },
        check: function (name) {
            return DATA.city.get();
            //return DATA.city.getId(name);
        }
    };

    widjet.PVZ = {
        get: function (cityName) {
            return DATA.PVZ.getCityPVZ(cityName);
        },
        check: function (cityName) {
            return DATA.PVZ.check(cityName);
        }
    };

    widjet.cargo = {
        add: function (item) {
            cargo.add(item);
        },
        reset: function () {
            cargo.reset();
        },
        get: function () {
            return cargo.get()
        }
    };

    widjet.calculate = function (pointId) {
        CALCULATION.calculate(pointId);
        return CALCULATION.data;
    };

    if (!widjet.options.get('link')) {
        widjet.open = function () {
            if (ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__sidebar-burger').hasClass('open'))
                ipjq(IDS.get('IPOL_DEMO_widget_cnt')).find('.IPOL_DEMO-widget__sidebar-burger').trigger('click');
            widjet.showLoader(true);
            if (widjet.zeroPointError) return;
            if (DATA.city.loaded == DATA.city.current) widjet.hideLoader();
            else template.controller.loadCity();
            template.controller.open();
        };
        widjet.close = function () {
            template.controller.close();
        };
    }

    return widjet;
}