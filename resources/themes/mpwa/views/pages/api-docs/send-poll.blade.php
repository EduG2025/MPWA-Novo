<div class="tab-pane fade  " id="sendpoll" role="tabpanel">
	<div class="d-flex mb-3 gap-3">
		<div>
			<span class="badge bg-label-primary rounded-2 p-2">
			<i class="ti tabler-chart-bar-popular icon-32px"></i>
			</span>
		</div>
		<div>
			<h4 class="mb-0 lh-sm">
				<span class="align-middle">Send Poll API</span>
			</h4>
			<small>Api Docs Sending Poll Messages</small>
		</div>
	</div>
	<div id="accordionPayment" class="accordion">
		<div class="card">
			<div class="card-body">
    <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
    <p>Endpoint: <code>{{ url('/') }}/send-poll</code></p>

    <p>Request Body : (JSON If POST)
    <table class="table">
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Type</th>
                <th>Required</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>api_key</td>
                <td>string</td>
                <td>Yes</td>
                <td>API Key</td>
            </tr>
            <tr>
                <td>sender</td>
                <td>string</td>
                <td>Yes</td>
                <td>Number of your device</td>
            </tr>
            <tr>
                <td>number</td>
                <td>string</td>
                <td>Yes</td>
                <td>recipient number ex 72888xxxx|62888xxxx</td>
            </tr>
            <tr>
                <td>name</td>
                <td>string</td>
                <td>Yes</td>
                <td>name or question polling</td>
            </tr>
            <tr>
                <td>option</td>
                <td>array</td>
                <td>Yes</td>
                <td>values of poll message</td>
            </tr>
            <tr>
                <td>countable</td>
                <td>string 1 or 0</td>
                <td>Yes</td>
                <td>is polling only one number / poll or allow multiple</td>
            </tr>
           
            

        </tbody>
    </table>
    <p>Example json</p>
<pre class="bg-dark rounded text-white"><code>{
    "sender" : "081222xxxxxx",
    "api_key" : "123456789",
    "number" : "201111xxxxxx",
    "countable" : "1",
    "name" : "what color do you like?",
    "option" : ["red","blue","yellow"]
}</code></pre>
    <p> Example URL</p>
<pre class="bg-dark rounded text-white"><code>{{ url('/') }}/send-button?sender=081222xxxxxx&api_key=123456789&number=201111xxxxxx&name=what color do you like&button=red,blue,yellow</code></pre>
    <p>Example JSON Response</p>
<pre class="bg-dark rounded text-white"><code>{
    "status":true,
    "msg":"Message sent successfully!"
}</code></pre>

</div>
</div>
</div>
</div>
