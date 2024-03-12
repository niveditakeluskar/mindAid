/**
 * Bootstrap Table Manager
 * Created by David Ludwig
 *
 * This will help control all sorts of features for a bootstrap table
 */

 const _ = require("underscore");

// Begin Table Class ----------------------------------------------------------

/**
 * Table class constructor
 *
 * @param {jQuery Object} table
 */
function Table(tableElement) {
    this.__booleanFields   = [];
    this.__clickable       = false;
    this.__columns         = [];
    this.__data            = {};
    this.__dataFiltered    = {};
    this.__dateIndex       = null;
    this.__element         = tableElement;
    this.__filterDateStart = null;
    this.__filterDateEnd   = null;
    this.__filterKeywords  = "";
    this.__keywordIndices  = [];
    this.__maxPage         = 0;
    this.__pagination      = true;
    this.__page            = 0;
    this.__perPage         = 15;

    this.__clickCallbacks = [];
};

// Private Methods ------------------------------------------------------------

/**
 * Filter the data
 */
Table.prototype.filterData = function() {
    this.__dataFiltered = util.filterValues(this.__data, this.__keywordIndices, this.__filterKeywords);
    if (this.__dateIndex && (this.__filterDateStart || this.__filterDateEnd)) {
        var filtered = {};
        for (let key in this.__dataFiltered) {
            let time = new Date(this.__dataFiltered[key][this.__dateIndex]);
            result = !this.__filterDateStart || this.__filterDateStart && time >= this.__filterDateStart;
            result = result && (!this.__filterDateEnd || (this.__filterDateEnd && time <= this.__filterDateEnd));
            if (result)
                filtered[key] = this.__dataFiltered[key];
        }
        this.__dataFiltered = filtered;
    }
};

/**
 * Adjust the current page if invalid
 */
Table.prototype.correctPage = function() {
    this.__maxPage = parseInt(Math.floor(Object.keys(this.__dataFiltered).length / this.__perPage));
    this.__page    = Math.max(Math.min(this.__page, this.__maxPage), 0);
};

/**
 * Render the pagination widget
 */
Table.prototype.renderPagination = function() {
    var prev  = this.__element.find(".pagination > .page-item").first();
    var next  = this.__element.find(".pagination > .page-item").last();
    var pages = [];
    for (let i = 0; i <= this.__maxPage; i++) {
        let page   = $("<li class='page-item'>");
        let button = $("<button class='page-link'>").attr("data-page", i).appendTo(page);
        if (i == this.__page) {
            page.addClass("active");
            button.html(`${i+1} <span class="sr-only">(current)</span>`);
        } else {
            button.text(i+1);
        }
        pages.push(page);
    }
    if (this.__page == 0)
        prev.addClass("disabled");
    else
        prev.removeClass("disabled");
    if (this.__page == this.__maxPage)
        next.addClass("disabled");
    else
        next.removeClass("disabled");
    this.__element.find(".pagination").html("").append([prev, ...pages, next]);
};

/**
 * Render the table
 *
 * @param {Array}   keys
 * @param {Boolean} fade
 */
Table.prototype.renderTable = function(keys, fade) {
    var rows = [];
    for (let i in keys) {
        let row = $("<tr>").attr("data-index", keys[i]);
        $("<th>").attr("scope", "row").text(this.__dataFiltered[keys[i]][this.__columns[0]]).appendTo(row);
        for (let j = 1; j < this.__columns.length; j++) {
            if (this.__booleanFields.indexOf(this.__columns[j]) != -1) {
                $("<td>").text(this.__dataFiltered[keys[i]][this.__columns[j]] ? "Yes" : "No").appendTo(row);
            } else {
                $("<td>").text(this.__dataFiltered[keys[i]][this.__columns[j]]).appendTo(row);
            }
        }
        rows.push(row);
    };
    if (fade) {
        this.__element.find("tbody").fadeOut(200, function() {
            $(this).html("").append(rows).fadeIn(200);
        });
    } else {
        this.__element.find("tbody").html("").append(rows);
    }
};

// General Methods ------------------------------------------------------------

/**
 * Register a row click listener
 *
 * @param {Function} callback
 */
Table.prototype.click = function(callback) {
    if (this.__clickable) {
        this.__clickCallbacks.push(callback);
    }
};

/**
 * Display the data onto the table
 *
 * @param  {Boolean}      fade Display the fading animation
 * @return {Table Object}
 */
Table.prototype.render = function(fade = false, callback = null) {
    var table = this;
    setTimeout(function() { // Run asynchronously
        table.filterData();
        let keys = Object.keys(table.__dataFiltered);
        if (table.__pagination) {
            table.correctPage();
            let indexOffset = table.__perPage * table.__page;
            keys = keys.slice(indexOffset, indexOffset + table.__perPage);
            table.renderPagination();
        }
        table.renderTable(keys, fade);
        if (callback) {
            callback(table);
        }
    }, 1);
};

/**
 * Setup the table with the given settings
 *
 * @param  {JSON Object}  settings
 * @return {Table Object}
 */
Table.prototype.setup = function(settings) {
    for (let key in settings) {
        if (this[`__${key}`] !== undefined) {
            this[`__${key}`] = settings[key];
        }
    }
    return this;
};

/**
 * Trigger an event
 *
 * @param  {String}
 * @return {Table Object}
 */
Table.prototype.trigger = function(event, ...args) {
    if (event == "click") {
        let dataIndex = args[0];
        let data = this.__data[dataIndex];
        this.__clickCallbacks.forEach(function(callback) {
            callback(data);
        });
    }
    return this;
};

// Accessors ------------------------------------------------------------------

/**
 * Get the table boolean fields
 *
 * @return {Array<String>}
 */
Table.prototype.booleanFields = function () {
    return JSON.parse(JSON.stringify(this.__booleanFields));
};

/**
 * Get the table columns
 *
 * @return {Array<String>}
 */
Table.prototype.columns = function () {
    return JSON.parse(JSON.stringify(this.__columns));
};

/**
 * Get all of the data in the table
 *
 * @return {Array}
 */
Table.prototype.data = function () {
    return JSON.parse(JSON.stringify(this.__data));
};

/**
 * Get the filtered data in the table
 *
 * @return {Array}
 */
Table.prototype.filteredData = function () {
    return JSON.parse(JSON.stringify(this.__dataFiltered));
};

/**
 * Get the table element
 *
 * @return {jQuery Object}
 */
Table.prototype.element = function() {
    return this.__element;
};

/**
 * Check if the table is clickable
 *
 * @return {Boolean}
 */
Table.prototype.isClickable = function() {
    return this.__clickable;
};

/**
 * Check if the loading animation is currently displayed
 *
 * @return {Boolean}
 */
Table.prototype.isLoading = function () {
    return this.__element.find(".table-loading").is(":visible");
};

/**
 * Check if pagination is enabled
 *
 * @return {Boolean}
 */
Table.prototype.isPaginationEnabled = function () {
    return this.__pagination;
};

/**
 * Get the current page
 *
 * @return {Integer}
 */
Table.prototype.page = function() {
    return this.__page;
};

/**
 * Get the number of rows to display per page
 *
 * @return {Integer}
 */
Table.prototype.perPage = function() {
    return this.__perPage;
};

// Mutators -------------------------------------------------------------------

/**
 * Set the list of boolean indices
 *
 * @return {Array<String>}
 */
Table.prototype.setBooleanFields = function (columns) {
    this.__booleanFields = [];
    columns.forEach((field) => {
        this.__booleanFields.push(field);
    });
    return this;
};

/**
 * Set the column list
 *
 * @return {Array<String>}
 */
Table.prototype.setColumns = function (columns, labels) {
    this.__columns = [];
    var head = $(this.__element).find("thead>tr");
    head.html("");
    for (var i in columns) {
        $("<th scope='col'>").html(labels[i]).appendTo(head);
        this.__columns.push(columns[i]);
    }
    return this;
};

/**
 * Set the data in the table
 *
 * @param  {Array|JSON Object} data
 * @return {Table Object}
 */
Table.prototype.setData = function(data, callback) {
    this.__data = {};
    return this.appendData(data, callback);
};

/**
 * Append some data to the existing data in the table
 *
 * @param {Array|JSON Object} data
 * @return {Table Object}
 */
Table.prototype.appendData = function(data, callback) {
    this.__data = {};
    for (let key in data)
        this.__data[key] = data[key];
    this.render(undefined, callback);
    return this;
};

/**
 * Filter the data by the given date range
 *
 * @param  {Date|Null} start
 * @param  {Date|Null} end
 * @return {Table Object}
 */
Table.prototype.setDateFilter = function(start = null, end = null, callback) {
    this.__filterDateStart = start ? new Date(start) : null;
    this.__filterDateEnd   = end   ? new Date(end)   : null;
    this.render(undefined, callback);
    return this;
};

/**
 * Set the beginning date to filter off from the rest of the data
 *
 * @param  {String}       keywords
 * @return {Table Object}
 */
Table.prototype.setKeywordFilter = function(keywords = "", callback) {
    this.__filterKeywords = keywords;
    this.render(undefined, callback);
    return this;
};

/**
 * Set the table's loading animation status
 *
 * @param {Boolean}       loading
 * @return {Table Object}
 */
Table.prototype.setLoading = function (loading) {
    var elem = this.__element.find(".table-loading");
    if (loading)
        elem.show();
    else
        elem.hide();
    return this;
};

/**
 * Set the current page
 *
 * @param  {Integer}      page
 * @return {Table Object}
 */
Table.prototype.setPage = function(page) {
    if (page != this.__page) {
        this.__page = page;
        this.render(true);
    }
    return this;
};

// End Table Class ------------------------------------------------------------

/**
 * Store all instances of tables
 */
var tables = { };

/**
 * Invoked when a row in a table was clicked
 *
 * @param {jQuery Object} row
 */
var clicked = function(row) {
    let name      = row.parents("[data-table]").attr("data-table");
    let table     = window.table(name);
    let dataIndex = row.attr("data-index");
    table.trigger("click", dataIndex);
};

/**
 * Invoked when a pagination button has been clicked
 *
 * @param {jQuery Object} button
 */
var pageClicked = function(button) {
    let name  = button.parents("[data-table]").attr("data-table");
    let table = window.table(name);
    if (button.attr("aria-label") == "Previous") {
        table.setPage(table.page() - 1);
    } else if (button.attr("aria-label") == "Next") {
        table.setPage(table.page() + 1);
    } else {
        table.setPage(parseInt(button.attr("data-page")));
    }
};

/**
 * Locate a table by its name and return a Table instance of it
 *
 * @param  {String}       name
 * @return {Table Object}
 */
window.table = function(name) {
    if (!tables[name]) {
        let table = $(`[data-table="${name}"]`);
        if (!table)
            return null;
        tables[name] = new Table(table);
    }
    return tables[name];
};

/**
 * Register event listeners
 */
$(document).ready(function() {
    $(document).on("click", "[data-table] tbody > tr", function() {
        clicked($(this));
    });
    $(document).on("click", "[data-table] .pagination .page-link", function() {
        pageClicked($(this));
    });  
});
