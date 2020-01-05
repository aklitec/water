

window.ConsumptionTable = class ConsumptionTable extends React.Component {
        constructor(props) {
            super();
            this.state = {
                consumptions: [],
                date: new Date().getFullYear(),
                fetch: true
            }
        }

        componentDidMount() {

            $("#prevYear").on('click', () => {
                let value = Number($("#prevYear").text());
                console.log('prev Value Date:', value)
                this.loadNotesFromServer(value)
            });

            $("#nextYear").on('click', () => {
                let value = Number($("#nextYear").text());
                console.log('Nex Value Date:', value)
                this.loadNotesFromServer(value)
            });
            this.loadNotesFromServer(this.state.date);
        }

        loadNotesFromServer = (value) => {
            $.ajax({
                url: `http://symfony.localhost/consumption/${this.props.id}/api/search/${value}`,
                success: function (data) {
                    this.setState({consumptions: data, date: value});
                    console.log('DATE', data)
                }.bind(this)
            });
        };

        render() {

            return (

                this.state.consumptions.map((c, index) => {
                    const newDate = new Date(c.date).toLocaleString('default', {month: 'short'})
                    return (
                        <tr key={index}>
                            <td> {newDate} </td>
                            <td><span className="tag"> {c.code}</span></td>
                            <td>{c.date}</td>
                            {console.log("state:", this.state.date)}
                            <td className="text-center">{c.previous_record}</td>
                            <td className="text-center">{c.current_record}</td>
                            <td className="text-center">{c.consumption}</td>
                            <td>{c.status}</td>
                            <td className="text-right">
                                {/*<a href="" className="btn btn-sm btn-outline-warning"><i className="fe fe-printer"></i></a>*/}
                            </td>

                        </tr>

                    )
                })

            )

        }

    }




