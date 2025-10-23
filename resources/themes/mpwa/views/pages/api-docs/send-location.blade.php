<div class="tab-pane fade " id="sendlocation" role="tabpanel">
	<div class="d-flex mb-3 gap-3">
		<div>
			<span class="badge bg-label-primary rounded-2 p-2">
			<i class="ti tabler-map-pin icon-32px"></i>
			</span>
		</div>
		<div>
			<h4 class="mb-0 lh-sm">
				<span class="align-middle">Send Location API</span>
			</h4>
			<small>Api Docs Sending Location Messages</small>
		</div>
	</div>
	<div id="accordionPayment" class="accordion">
		<div class="card">
			<div class="card-body">
    <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
    <p>Endpoint: <code>{{ url('/') }}/send-location</code></p>

    <p>Request Body : (JSON If POST) </p>
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
                <td>latitude</td>
                <td>string</td>
                <td>Yes</td>
                <td>latitude number ex 24.121231</td>
            </tr>
			<tr>
                <td>Longitude</td>
                <td>string</td>
                <td>Yes</td>
                <td>longitude number ex 55.1121221</td>
            </tr>
        </tbody>
    </table>
    <br>
    <p>Example JSON Request</p>
<pre class="bg-dark rounded text-white"><code>{
    "api_key": "1234567890",
    "sender": "62888xxxx",
    "number": "62888xxxx",
    "latitude": "24.121231",
    "longitude": "55.1121221"
}</code></pre>
    <p>Example URL Request</p>
<pre class="bg-dark rounded text-white"><code class="json">{{ url('/') }}/send-location?api_key=1234567890&sender=62888xxxx&number=62888xxxx&latitude=24.121231&longitude=55.1121221</code></pre>
    <p>Example JSON Response</p>
<pre class="bg-dark rounded text-white"><code>{
    "status":true,
    "msg":"Message sent successfully!"
}</code></pre>

</div>
</div>
</div>
</div>
